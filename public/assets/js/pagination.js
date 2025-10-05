$(document).ready(function() {
    $(document).on('click', 'ul.pagination a.page-link.prev', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const $ul = $(e.target).closest('ul.pagination');
        const base_url = $ul.data('base-url');
        const per_page = $ul.data('per-page');
        const curd_page = $ul.data('curd-page');
        const total_page = $ul.data('total-page');

        const page_num = curd_page - 1;
        if(page_num < 1) return;

        const offset = (page_num - 1) * per_page;
        const url = `${base_url}?per_page=${per_page}&offset=${offset}`;

        window.location.href = url;
    })

    $(document).on('click', 'ul.pagination a.page-link.next', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const $ul = $(e.target).closest('ul.pagination');
        const base_url = $ul.data('base-url');
        const per_page = $ul.data('per-page');
        const curd_page = $ul.data('curd-page');
        const total_page = $ul.data('total-page');

        const page_num = curd_page + 1;
        if(page_num > total_page) return;

        const offset = (page_num - 1) * per_page;
        const url = `${base_url}?per_page=${per_page}&offset=${offset}`;

        window.location.href = url;
    })

    $(document).on('click', 'ul.pagination a.page-link.goto-page', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const $ul = $(e.target).closest('ul.pagination');
        const base_url = $ul.data('base-url');
        const per_page = $ul.data('per-page');
        const curd_page = $ul.data('curd-page');
        const total_page = $ul.data('total-page');
        const page_num = $(e.target).data('page-num');

        if(page_num < 1 || page_num > total_page) return;

        const offset = (page_num - 1) * per_page;
        const url = `${base_url}&per_page=${per_page}&offset=${offset}`;

        window.location.href = url;
    })
})