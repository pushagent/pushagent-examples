# Embed Push Agent on any website

Two things are needed on any HTTPS website:

1. **The script**, on every page, just before `</body>`:

   ```html
   <script src="https://app.pushagent.net/embed?id=SITE_ID" async></script>
   ```

   Copy the exact line, with your own site ID, from **Integration** in your [Push Agent dashboard](https://app.pushagent.net).

2. **The service worker file**, `pushagent-sw.js`, in the **root folder** of your domain, so it opens at `https://your-site.com/pushagent-sw.js`. Download it from the same Integration page. It must not be in a sub-folder.

Then click **Verify integration** in the dashboard.

## Where to paste the script

| Your site | Where the line goes |
|---|---|
| Hand-coded HTML | Before `</body>` in every page, or in a shared footer include |
| PHP site or framework (Laravel, CodeIgniter, Symfony…) | Your main layout or footer template |
| Static site generator (Hugo, Jekyll, Eleventy, Astro…) | The base layout / footer partial |
| WordPress | Use the [WordPress plugin](https://github.com/pushagent/pushagent-wordpress) instead |
| Joomla | Use the [Joomla plugin](https://github.com/pushagent/pushagent-joomla) instead |

Website builders that don't let you upload a file to the root of your domain can't host the service worker, so web push won't work there.

## Troubleshooting

- **Verify says the code is missing:** clear your page cache or CDN, then check the page source for `app.pushagent.net/embed`.
- **Verify says the service worker is missing:** open `https://your-site.com/pushagent-sw.js` in your browser. A "Not found" page means the file is in the wrong folder or has a different name.
- **The "Allow notifications" card doesn't appear:** use a private window and an `https://` address. If you already allowed or blocked notifications on your site, reset the permission from the padlock icon in the address bar.

Full guide with screenshots: <https://pushagent.net/docs/add-push-notifications-to-any-website/>
