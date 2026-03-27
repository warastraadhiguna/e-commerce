<script>
    document.addEventListener('DOMContentLoaded', function () {
        var currentUrl = new URL(window.location.href);
        var scrollPosition = parseInt(currentUrl.searchParams.get('_scroll') || '', 10);

        if (! Number.isNaN(scrollPosition)) {
            window.scrollTo({
                top: Math.max(scrollPosition, 0),
                behavior: 'auto',
            });

            currentUrl.searchParams.delete('_scroll');
            window.history.replaceState({}, document.title, currentUrl.pathname + currentUrl.search + currentUrl.hash);
        }

        document.querySelectorAll('form[data-preserve-scroll="true"]').forEach(function (form) {
            form.addEventListener('submit', function () {
                var redirectInput = form.querySelector('input[name="redirect_to"]');

                if (! redirectInput) {
                    return;
                }

                var redirectUrl = new URL(window.location.href);

                redirectUrl.searchParams.delete('_scroll');
                redirectUrl.searchParams.set('_scroll', String(Math.max(window.scrollY || window.pageYOffset || 0, 0)));

                redirectInput.value = redirectUrl.pathname + redirectUrl.search + redirectUrl.hash;
            });
        });
    });
</script>
