<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0" 
                xmlns:html="http://www.w3.org/TR/REC-html40"
                xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
	<xsl:template match="/">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<title>XML Sitemap - Lab Pemrograman &amp; Komputasi</title>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<style type="text/css">
				body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif; color: #334155; margin: 0; padding: 20px; background: #f8fafc; }
				.container { max-width: 1000px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
				h1 { color: #1e293b; font-size: 24px; margin-bottom: 20px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; }
				table { width: 100%; border-collapse: collapse; margin-top: 10px; }
				th { background: #f1f5f9; text-align: left; padding: 12px; font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid #e2e8f0; }
				td { padding: 12px; border-bottom: 1px solid #f1f5f9; font-size: 14px; word-break: break-all; }
				tr:hover td { background: #f8fafc; }
				a { color: #2563eb; text-decoration: none; font-weight: 500; }
				a:hover { text-decoration: underline; }
				.priority { font-weight: bold; color: #059669; }
			</style>
		</head>
		<body>
			<div class="container">
				<h1>Sitemap Index - Lab Siskom</h1>
				<p>Sitemap ini dihasilkan secara otomatis oleh sistem untuk mesin pencari (Google/Bing).</p>
				<table>
					<tr>
						<th>URL</th>
						<th>Priority</th>
						<th>Change Freq</th>
						<th>Last Modified</th>
					</tr>
					<xsl:for-each select="sitemap:urlset/sitemap:url">
						<tr>
							<td>
								<xsl:variable name="itemURL">
									<xsl:value-of select="sitemap:loc"/>
								</xsl:variable>
								<a href="{$itemURL}"><xsl:value-of select="sitemap:loc"/></a>
							</td>
							<td><span class="priority"><xsl:value-of select="sitemap:priority"/></span></td>
							<td><xsl:value-of select="sitemap:changefreq"/></td>
							<td><xsl:value-of select="sitemap:lastmod"/></td>
						</tr>
					</xsl:for-each>
				</table>
			</div>
		</body>
		</html>
	</xsl:template>
</xsl:stylesheet>