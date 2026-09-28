document.addEventListener('DOMContentLoaded', function () {
    const deleteBtns = document.querySelectorAll('.btn-del');
    deleteBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            if (!confirm('هل تريد حذف هذا السجل بشكل نهائي؟')) {
                e.preventDefault();
            }
        });
    });
});
