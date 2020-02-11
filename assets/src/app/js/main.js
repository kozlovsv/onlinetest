/** отмена создания записи */
$(document).on('click', '.form-cancel', function () {
    var modal = $(this).closest('.modal');
    if (modal.length) {
        modal.modal('hide');
        return false;
    }
});

/** подтвердить действие */
$(document).on("mouseenter", "a[data-text]", function () {
    $(this).confirm({
        title: "Подтвердите действие",
        confirmButton: "Да",
        cancelButton: "Нет",
        confirmButtonClass: "btn-info",
        cancelButtonClass: "btn-default",
        dialogClass: "modal-dialog"
    });
});

/** Пресекать множественный сабмит форм */
$(document).on('submit', 'form', function() {
    $(this).find('[type="submit"]').addClass('hide');
    return true;
});