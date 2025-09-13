<?php
// app/views/partials/modals/res_modal.php
?>

<!-- Modal RES -->
<div class="modal fade" id="resModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dodaj Kartę Mieszkańca</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?action=handle-card">
                <input type="hidden" name="card_type" value="res">
                <input type="hidden" name="action" value="add">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Data zameldowania *</label>
                                <input type="date" class="form-control" name="data_zam" required
                                       value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Osiedle *</label>
                                <select class="form-select" name="osiedle" required>
                                    <option value="">Wybierz osiedle</option>
                                    <option value="Śródmieście">Śródmieście</option>
                                    <option value="Krzeczów">Krzeczów</option>
                                    <option value="Niepodległości">Niepodległości</option>
                                    <option value="Planty">Planty</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ulica *</label>
                                <input type="text" class="form-control" name="ulica" required
                                       placeholder="np. Kazimierza Wielkiego">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Nr domu *</label>
                                <input type="text" class="form-control" name="nr_domu" required
                                       placeholder="np. 15">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">Nr mieszkania</label>
                                <input type="text" class="form-control" name="nr_mieszkania"
                                       placeholder="np. 5">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button type="submit" class="btn btn-primary">Dodaj kartę</button>
                </div>
            </form>
        </div>
    </div>
</div>