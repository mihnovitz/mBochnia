<?php
// app/views/partials/modals/edit_modals.php
// This file will be dynamically included in the cards view when needed
?>

<?php if (isset($mka_data) && $mka_data): ?>
    <!-- Edit Modal MKA -->
    <div class="modal fade" id="editMkaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edytuj kartę MKA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="index.php?action=handle-card">
                    <input type="hidden" name="card_type" value="mka">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id_karty" value="<?php echo $mka_data['id_karty']; ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Typ karty *</label>
                            <select class="form-select" name="typ_karty" required>
                                <option value="normalny" <?php echo $mka_data['typ_karty'] == 'normalny' ? 'selected' : ''; ?>>Normalny</option>
                                <option value="ulgowy" <?php echo $mka_data['typ_karty'] == 'ulgowy' ? 'selected' : ''; ?>>Ulgowy</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Strefa *</label>
                            <select class="form-select" name="strefa" required>
                                <option value="1" <?php echo $mka_data['strefa'] == 1 ? 'selected' : ''; ?>>Strefa 1</option>
                                <option value="2" <?php echo $mka_data['strefa'] == 2 ? 'selected' : ''; ?>>Strefa 2</option>
                                <option value="3" <?php echo $mka_data['strefa'] == 3 ? 'selected' : ''; ?>>Strefa 3</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                        <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (isset($rpk_data) && $rpk_data): ?>
    <!-- Edit Modal RPK -->
    <div class="modal fade" id="editRpkModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edytuj kartę RPK</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="index.php?action=handle-card">
                    <input type="hidden" name="card_type" value="rpk">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id_karty" value="<?php echo $rpk_data['id_karty']; ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Typ karty *</label>
                            <select class="form-select" name="typ_karty" required>
                                <option value="normalny" <?php echo $rpk_data['typ_karty'] == 'normalny' ? 'selected' : ''; ?>>Normalny</option>
                                <option value="ulgowy" <?php echo $rpk_data['typ_karty'] == 'ulgowy' ? 'selected' : ''; ?>>Ulgowy</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                        <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>