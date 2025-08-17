<footer class="content-footer footer bg-footer-theme">
    <div class="container-fluid d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
        <div class="mb-2 mb-md-0">
            {{ config('dashboard.footer.copy-right') }}
        </div>
        <div>
            <a href="{{ config('dashboard.footer.link1.url') }}" class="footer-link me-4" target="_blank">
                {{ config('dashboard.footer.link1.text') }}
            </a>
        </div>
    </div>
</footer>
