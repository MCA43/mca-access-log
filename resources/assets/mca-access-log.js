/* mca/access-log — confirm forms: mca-ui (data-mca-confirm). Pagination SVG size fallback. */
(function () {
    function sizePaginationIcons() {
        document.querySelectorAll('.mca-alog-pagination nav svg, nav.mca-ui-pagination svg').forEach(function (svg) {
            svg.setAttribute('width', '20');
            svg.setAttribute('height', '20');
            svg.style.width = '1.25rem';
            svg.style.height = '1.25rem';
            svg.style.maxWidth = '1.25rem';
            svg.style.maxHeight = '1.25rem';
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', sizePaginationIcons);
    } else {
        sizePaginationIcons();
    }
})();
