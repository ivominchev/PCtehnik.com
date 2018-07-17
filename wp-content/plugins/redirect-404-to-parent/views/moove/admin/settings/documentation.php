<div class="moove-redirect-plugin-documentation" style="max-width: 50%;">
    <br>
    <h1 id="redirect-404-to-parent-base---wordpress-plugin">Redirect 404 to parent base - Plugin Documentation</h1>

    <p>This plugin helps you define redirect rules that will redirect any 404 request under a defined URL base to the parent URL base.</p>
    <p>Simply put, it does this:
        <strong>&quot;When there is a 404 found for an URL like /about/dummy-url =&gt; redirect the visitor to /about/.&quot;</strong>
    </p>
    <p>
        <em>Note: This plugin is not intended to offer redirect rule creation features for any kind of redirection within the WordPress system. It simply resolves a problem via Plugin not custom functions or template code.</em>
    </p>

    <h2 id="features">Features</h2>

    <p>The plugin adds an option/settings page where you can set up these redirects easily.</p>
    <p>The following features are included in this plugin:</p>
    <ul>
        <li>You can defined the BASE URL - this is the URL that will be served as a starting point.</li>
        <li>You can define the type of the redirection done by WordPress (302, 301, etc.).</li>
        <li>You can add as many rules you want and easily delete them if you don't need them anymore</li>
        <li>The plugin checks if you already added a rule based on the slug, so you won't add the same rule twice.</li>
        <li>The plugin checks if the URL you are setting up as a BASE exists in WordPress as a post or page, so you're not creating an erroneous URL base.</li>
        <li>You can see a log of the 404 redirected by the plugin (if the plugin registers any 404 errors), so you can easily identify what URLs are generating the 404 errors.</li>
        <li>If there are more then 10 rows in the 404 log/statistics, you can download the whole log for an URL in CSV format.</li>
    </ul>

    <h2 id="example-use-case">Example Use Case</h2>
    <p>
        <strong>Base URL (set up in this plugin as a rule):</strong> http://yourdomain.com/sample-page/
        <strong>Target URL:</strong> http://yourdomain.com/sample-page/non-existing-page
    </p>
    <p>In this case if a visitor try to access the <strong>TARGET URL</strong>, WordPress returns a 404 error/page by default because the page/post doesn't exist.</p>
    <p>This plugin will automatically redirect the visitor to http://yourdomain.com/sample-page/ instead of letting the visitor end up on a 404 page.</p>
</div>
<!-- moove-redirect-plugin-documentation -->