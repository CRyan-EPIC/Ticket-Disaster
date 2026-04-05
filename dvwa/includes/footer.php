<?php $theme = getThemeConfig(); ?>

            </div><!-- /content-area -->

            <div class="app-footer">
                <?php echo htmlspecialchars($theme['name']); ?> &mdash; <em style="color:<?php echo $theme['secondary']; ?>;opacity:0.6;"><?php echo $theme['motto']; ?></em> &mdash;
                Inspired by <a href="https://github.com/digininja/DVWA" target="_blank">DVWA</a>
                &mdash; For educational purposes only
            </div>
        </div><!-- /main-content -->
    </div><!-- /app-layout -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function isMobile() {
        return window.innerWidth <= 768;
    }

    function toggleSidebar() {
        // Remove the no-transition init class so the toggle animates
        document.documentElement.classList.remove('td-sb-retracted');
        var sidebar = document.getElementById('sidebar');
        var main = document.getElementById('mainContent');
        var overlay = document.getElementById('sidebarOverlay');
        if (isMobile()) {
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('visible');
        } else {
            var retracted = sidebar.classList.toggle('retracted');
            main.classList.toggle('sidebar-retracted', retracted);
            localStorage.setItem('ticketdisaster_sidebar', retracted ? 'retracted' : 'expanded');
        }
    }

    function toggleSettings() {
        document.documentElement.classList.remove('td-sb-settings-collapsed');
        var links = document.getElementById('settingsLinks');
        var chevron = document.getElementById('settingsChevron');
        var collapsed = links.classList.toggle('collapsed');
        if (chevron) chevron.classList.toggle('rotated', collapsed);
        localStorage.setItem('ticketdisaster_settings', collapsed ? 'collapsed' : 'expanded');
    }

    // Sync element classes with the state already applied by the <head> script
    (function() {
        if (!isMobile() && localStorage.getItem('ticketdisaster_sidebar') === 'retracted') {
            document.getElementById('sidebar').classList.add('retracted');
            document.getElementById('mainContent').classList.add('sidebar-retracted');
        }
        if (localStorage.getItem('ticketdisaster_settings') === 'collapsed') {
            var links = document.getElementById('settingsLinks');
            var chevron = document.getElementById('settingsChevron');
            if (links) links.classList.add('collapsed');
            if (chevron) chevron.classList.add('rotated');
        }
    })();
    </script>
</body>
</html>
