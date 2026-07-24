import json
import os

new_keys = {
  "Audit Trail": { "en": "Audit Trail", "fr": "Journal d'Audit", "id": "Jejak Audit" },
  "Audit Trail & Activity Log": { "en": "Audit Trail & Activity Log", "fr": "Journal d'Audit & Historique", "id": "Jejak Audit & Log Aktivitas" },
  "Read-only system record of all critical activities.": { "en": "Read-only system record of all critical activities.", "fr": "Enregistrement système en lecture seule de toutes les activités critiques.", "id": "Catatan sistem hanya-baca dari semua aktivitas kritis." },
  "Secure Log": { "en": "Secure Log", "fr": "Journal Sécurisé", "id": "Log Aman" },
  "No activities recorded yet.": { "en": "No activities recorded yet.", "fr": "Aucune activité enregistrée pour le moment.", "id": "Belum ada aktivitas yang direkam." },
  "Date & Time": { "en": "Date & Time", "fr": "Date & Heure", "id": "Tanggal & Waktu" },
  "Causer (User)": { "en": "Causer (User)", "fr": "Auteur (Utilisateur)", "id": "Pelaku (Pengguna)" },
  "Event & Description": { "en": "Event & Description", "fr": "Événement & Description", "id": "Acara & Deskripsi" },
  "Changes / Properties": { "en": "Changes / Properties", "fr": "Modifications / Propriétés", "id": "Perubahan / Properti" },
  "Role:": { "en": "Role:", "fr": "Rôle :", "id": "Peran:" },
  "Subject:": { "en": "Subject:", "fr": "Sujet :", "id": "Subjek:" },
  "Old:": { "en": "Old:", "fr": "Ancien :", "id": "Lama:" },
  "New:": { "en": "New:", "fr": "Nouveau :", "id": "Baru:" },
  "No additional data": { "en": "No additional data", "fr": "Aucune donnée supplémentaire", "id": "Tidak ada data tambahan" },
  "User logged in": { "en": "User logged in", "fr": "Utilisateur connecté", "id": "Pengguna masuk" },
  "Generated LPJ Preview": { "en": "Generated LPJ Preview", "fr": "Aperçu LPJ généré", "id": "Pratinjau LPJ dibuat" },
  "Exported LPJ PDF": { "en": "Exported LPJ PDF", "fr": "LPJ exporté en PDF", "id": "LPJ diekspor ke PDF" },
  "Exported LPJ Excel": { "en": "Exported LPJ Excel", "fr": "LPJ exporté en Excel", "id": "LPJ diekspor ke Excel" },
  "Member created": { "en": "Member created", "fr": "Membre créé", "id": "Anggota dibuat" },
  "Member updated": { "en": "Member updated", "fr": "Membre mis à jour", "id": "Anggota diperbarui" },
  "Member deleted": { "en": "Member deleted", "fr": "Membre supprimé", "id": "Anggota dihapus" },
  "Transaction created": { "en": "Transaction created", "fr": "Transaction créée", "id": "Transaksi dibuat" },
  "Transaction updated": { "en": "Transaction updated", "fr": "Transaction mise à jour", "id": "Transaksi diperbarui" },
  "Transaction deleted": { "en": "Transaction deleted", "fr": "Transaction supprimée", "id": "Transaksi dihapus" },
  "Fund created": { "en": "Fund created", "fr": "Fonds créé", "id": "Dana dibuat" },
  "Fund updated": { "en": "Fund updated", "fr": "Fonds mis à jour", "id": "Dana diperbarui" },
  "Fund deleted": { "en": "Fund deleted", "fr": "Fonds supprimé", "id": "Dana dihapus" },
  "Category created": { "en": "Category created", "fr": "Catégorie créée", "id": "Kategori dibuat" },
  "Category updated": { "en": "Category updated", "fr": "Catégorie mise à jour", "id": "Kategori diperbarui" },
  "Category deleted": { "en": "Category deleted", "fr": "Catégorie supprimée", "id": "Kategori dihapus" }
}

for lang in ['en', 'fr', 'id']:
    filepath = f"lang/{lang}.json"
    if os.path.exists(filepath):
        with open(filepath, 'r', encoding='utf-8') as f:
            data = json.load(f)
    else:
        data = {}
        
    for k, v in new_keys.items():
        data[k] = v[lang]
        
    with open(filepath, 'w', encoding='utf-8') as f:
        json.dump(data, f, indent=4, ensure_ascii=False)

print("Updated translation files successfully!")
