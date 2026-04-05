<?php
$page_title = 'About';
$base_path = '';
require_once 'dvwa/includes/header.php';
$theme = getThemeConfig();
?>

<div class="page-header">
    <h1 class="page-title"><i class="fas fa-info-circle"></i> About <?php echo htmlspecialchars($theme['name']); ?></h1>
    <p class="page-subtitle"><?php echo htmlspecialchars($theme['tagline']); ?></p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="text-purple mb-3">What is <?php echo htmlspecialchars($theme['name']); ?>?</h4>
                <p><?php echo htmlspecialchars($theme['about_what']); ?> It is a reimagining of the classic <a href="https://github.com/digininja/DVWA" target="_blank" class="text-purple">Damn Vulnerable Web Application (DVWA)</a> by Robin Wood, with a contemporary design and a fun theme.</p>

                <p>Its purpose is to provide a legal, safe environment for security professionals, students, and developers to practice common web vulnerabilities and learn about secure coding practices.</p>

                <h4 class="text-purple mb-3 mt-4">Vulnerability Modules</h4>
                <div class="table-responsive">
                    <table class="table table-dark">
                        <thead>
                            <tr>
                                <th>Module</th>
                                <th>Theme</th>
                                <th>Vulnerability</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($theme['about_modules'] as $mod): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($mod[0]); ?></td>
                                <td><?php echo htmlspecialchars($mod[1]); ?></td>
                                <td><?php echo htmlspecialchars($mod[2]); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <h4 class="text-purple mb-3 mt-4">Security Levels</h4>
                <p>Each vulnerability has four difficulty levels:</p>
                <ul>
                    <li><strong class="text-danger">Low</strong> &mdash; No security at all. Learn how the vulnerability works.</li>
                    <li><strong class="text-warning">Medium</strong> &mdash; Common but flawed defenses. Learn to bypass weak protections.</li>
                    <li><strong class="text-success">High</strong> &mdash; Stronger defenses, still exploitable. Requires creative thinking.</li>
                    <li><strong style="color: #06b6d4;">Impossible</strong> &mdash; Properly secured code. Learn the correct way to defend.</li>
                </ul>

                <h4 class="text-purple mb-3 mt-4">Disclaimer</h4>
                <div class="alert alert-warning">
                    <strong>WARNING:</strong> This application is intentionally vulnerable. Do NOT deploy it on a public-facing server or production environment. Use it only in controlled lab environments for educational purposes.
                </div>

                <h4 class="text-purple mb-3 mt-4">Credits</h4>
                <p>
                    Based on <a href="https://github.com/digininja/DVWA" target="_blank" class="text-purple">DVWA</a> by Robin Wood (<a href="https://digi.ninja" target="_blank" class="text-purple">digi.ninja</a>).
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'dvwa/includes/footer.php'; ?>
