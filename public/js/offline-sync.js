// Initialize IndexedDB
let dbPromise;

if (window.idb) {
    dbPromise = idb.openDB('skb_offline_db', 1, {
        upgrade(db) {
            if (!db.objectStoreNames.contains('sync_queue')) {
                db.createObjectStore('sync_queue', { keyPath: 'id', autoIncrement: true });
            }
        }
    });
} else {
    console.warn('IDB library not loaded, offline sync will not work.');
}

/**
 * Add a request payload to the offline IndexedDB queue.
 */
async function addRequestToQueue(url, method, payload, csrfToken) {
    if (!dbPromise) return false;
    
    try {
        const db = await dbPromise;
        const tx = db.transaction('sync_queue', 'readwrite');
        const store = tx.objectStore('sync_queue');
        
        await store.add({
            url: url,
            method: method,
            payload: payload,
            csrfToken: csrfToken,
            timestamp: new Date().getTime()
        });
        
        await tx.done;
        console.log('Saved to offline sync queue.');
        return true;
    } catch (e) {
        console.error('Failed to save to sync queue:', e);
        return false;
    }
}

/**
 * Process the queue: read all pending requests and send them.
 */
async function processQueue() {
    if (!dbPromise || !navigator.onLine) return;
    
    let isProcessing = false;
    try {
        const db = await dbPromise;
        const tx = db.transaction('sync_queue', 'readonly');
        const store = tx.objectStore('sync_queue');
        const requests = await store.getAll();
        
        if (requests.length === 0) return;
        
        console.log(`Processing ${requests.length} offline requests...`);
        isProcessing = true;
        
        for (const req of requests) {
            try {
                const response = await fetch(req.url, {
                    method: req.method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': req.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(req.payload)
                });
                
                if (response.ok) {
                    // Remove from queue upon success
                    const delTx = db.transaction('sync_queue', 'readwrite');
                    await delTx.objectStore('sync_queue').delete(req.id);
                    await delTx.done;
                    console.log(`Synced request ID: ${req.id}`);
                } else {
                    console.error(`Failed to sync request ID: ${req.id} with status ${response.status}`);
                    // Optionally, if status is 4xx (bad request), we might want to delete it anyway to prevent infinite looping
                    if (response.status >= 400 && response.status < 500) {
                        const delTx = db.transaction('sync_queue', 'readwrite');
                        await delTx.objectStore('sync_queue').delete(req.id);
                    }
                }
            } catch (fetchErr) {
                console.error(`Network error while syncing request ID: ${req.id}`, fetchErr);
                // Stop processing the rest of the queue if network drops again
                break;
            }
        }
        
    } catch (e) {
        console.error('Error processing sync queue:', e);
    } finally {
        if (isProcessing) {
            // Check if queue is fully cleared
            const db = await dbPromise;
            const remaining = await db.transaction('sync_queue', 'readonly').objectStore('sync_queue').count();
            if (remaining === 0) {
                console.log('Offline queue is now empty.');
                // Show a brief sync success toast if one exists globally
                if (typeof showToast === 'function') {
                    const el = document.getElementById('toast-message');
                    if (el) el.innerText = 'Offline data synchronized!';
                    showToast();
                }
            }
        }
    }
}

// 1. Listen for connection returning
window.addEventListener('online', () => {
    console.log('Network connected. Attempting background sync...');
    processQueue();
});

// 2. The Tech Lead Edge Case: Purge queue on initial load if online
document.addEventListener('DOMContentLoaded', () => {
    if (navigator.onLine) {
        // Add a slight delay so it doesn't block critical page rendering
        setTimeout(processQueue, 1500);
    }
});
