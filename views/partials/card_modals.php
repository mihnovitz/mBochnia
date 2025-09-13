<?php
// app/views/partials/card_modals.php
$cardController = new CardController();
?>

<!-- Modal MKA -->
<div class="modal fade" id="mkaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Dodaj kartę MKA</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <form method="POST" action="/public/index.php?action=handle-card">
                <input type="hidden" name="card_type" value="mka">
                <input type="hidden" name="action" value="add">
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Typ karty *</label><select class="form-select" name="typ_karty" required><option value="">Wybierz typ karty</option><option value="normalny">Normalny</option><option value="ulgowy">Ulgowy</option></select></div>
                    <div class="mb-3"><label class="form-label">Strefa *</label><select class="form-select" name="strefa" required><option value="">Wybierz strefę</option><option value="1">Strefa 1</option><option value="2">Strefa 2</option><option value="3">Strefa 3</option></select></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button><button type="submit" class="btn btn-primary">Dodaj kartę</button></div>
            </form>
        </div>
    </div>
</div>

<!-- QR Modals and other modals would follow the same pattern -->
<!-- ... rest of the modals from your original modals_add.php ... -->