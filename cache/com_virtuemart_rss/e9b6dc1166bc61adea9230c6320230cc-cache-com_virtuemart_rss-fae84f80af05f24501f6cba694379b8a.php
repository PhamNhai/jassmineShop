<?php die("Access Denied"); ?>#x#a:2:{s:6:"result";a:5:{i:0;O:8:"stdClass":3:{s:4:"link";s:69:"https://virtuemart.net/news/490-virtuemart-3-4-prepare-for-the-future";s:5:"title";s:37:"Virtuemart 3.4 prepare for the future";s:11:"description";s:13979:"<p>This release is now ready for all our VirtueMart users.</p>
<p>Due to the wide ranging changes we have made to underlying core functions and the removal of old VirtueMart and Joomla compatibility (which was weighing things down and slowing future developments), we have taken more time putting this release together than usual. Initial feedback from our beta testers has shown us that it has been worth the extra effort and time that it took.</p>
<p><strong>Here are some highlights:-</strong></p>
<p><b>Improved core</b> - VirtueMart core now benefits from using the Joomla classloader providing a more performant and more failproof method for classloading (classes are registered automatically and loaded if need.)</p>
<p><b>Ready for Joomla 3.9</b> - The core is ready for Joomla 3.9 and we expect that it will be relatively simple and fast to adapt for Joomla 4</p>
<p><b>PHP 7.2 - compatible</b> - VirtueMart 3.4 is now php7.2 compatible, users can now benefit from more secure and faster PHP versions.</p>
<p><b>Javascript updates</b> - We started to rewrite the javascripts to use data-vm instead of classes or ids - fallbacks are provided.</p>
<p><b>VirtueMart Package improvements</b> - This now automatically installs the VirtueMart Core, AIO, vmBeez3 template and the TCPDF. The installers within the package can still be used individually for those not requiring the full installation.</p>
<p><b>Next release in progress</b> - Expect to see two new payments, eWay and PayPal's “Smart Buttons”. A new template option which loads for example layouts with a bs4 prefix allows us to develop a completely new frontend template, whilst keeping backward compatibility. We are also planning for a new backend template, for which suggestions are welcome in the forum.</p>
<div class="special-download">
<p style="text-align: center;"><a class="button-primary" href="https://virtuemart.net/download">DOWNLOAD VM3 NOW<br /> VirtueMart 3 component (core and AIO)</a></p>
<p style="text-align: center;">&nbsp;</p>
</div>
<h2>Enhancements</h2>
<p><strong>PHP 7.1/2 support</strong></p>
<ul>
<li>Encryption and decryption with openssl for PHP 7.2 compatibility.</li>
</ul>
<p><strong>Order model changes</strong></p>
<ul>
<li>New trigger plgVmOnUpdateSingleItem.</li>
<li>Extra variable $inputOrder to the old triggers plgVmOnUpdateOrderShipment and plgVmOnUpdateOrderPayment.</li>
</ul>
<p><strong>Products</strong></p>
<ul>
<li>New feature "maximum products", "maximum customers" per vendor and "force product pattern".</li>
<li>Product is loaded for an order even when unpublished.</li>
<li>New filter for customprototypes in the product listing.</li>
<li>Products can be assigned a fixed Canonical Category – useful where a product is in multiple categories and the category name forms part of the product URL - product_canon_category_id.</li>
<li>Admin product list - A bulleted list for categories is now shown.&nbsp; Canonical category is highlighted.</li>
<li>Product model, getProduct, customfields are always loaded.</li>
<li>For FE search of items - the product model no longer replaces the search character “-“ with “%”.</li>
<li>Table change to products_language tables, “product_desc” now set as Datatype “text” (no varchars any longer).</li>
</ul>
<p><strong>Customfields</strong></p>
<ul>
<li>Admin feature to transform a set default list of "S" customfield to another and updates the values in the products that use the transformed customfield. transformSetStringsList.</li>
<li>Customfields can have the same name using a hidden configuration “unique_customfield_titles” to disable “unique names”.</li>
<li>New submenu to Joomla to the customfields list.</li>
<li>New method to calculate the variant price inside the function "getProductPrice" just using TRUE&nbsp;for the second parameter instead of a float&nbsp;</li>
</ul>
<p><strong>Currency handling</strong></p>
<ul>
<li>Calculation of net price rounds final price first (prevents wrong inputs) – calculatorH.</li>
<li>CurrencyDisplay roundForDisplay we round first before we multiply by quantity (as in the calculator), but not in Rappenrounding mode.</li>
<li>Rounding in CurrencyDisplay uses "round only display config".</li>
<li>Small change of rounding in currencydisplay using a different currency.</li>
<li>New option shared to currency admin views. (Program logic is maybe not complete).</li>
</ul>
<p><strong>Image handling</strong></p>
<ul>
<li>Images createThumb is only executed, when file not available. Forcing of thumb creation is done by deletion.</li>
<li>Search for unused media in admin.</li>
</ul>
<p><strong>User switch by admin FE</strong></p>
<ul>
<li>New hidden config switches:</li>
<li>ChangeShopperAlsoUseAdminShoppergroups - Add the shoppergroups of the logged in Admin user to the “switched” user’s shoppergroups.</li>
<li>ChangeShopperDeleteCart - When a user is chosen by the admin – the cart contents are cleared – prevents accidental inclusion of cart items.</li>
</ul>
<p><strong>Orders and checkout</strong></p>
<ul>
<li>Cart handling a change of the quantity is included in the popup and as extra warning message in the cart.- checkForQuantities changed vmInfo from vmWarn.</li>
<li>The layout ‘padded’ has a small update to show all quantity warnings in the popup.</li>
<li>products can use html classes in the cart item row</li>
<li>Checkout user data is stored, even when the userfield validation fails (the validation is for the checkout process).</li>
<li>Some work on the $cart-&gt;orderdoneHtml = $html; thematic (in vmpsplugin.php).</li>
<li>Admin Order list, more intuitive sequence for the columns.</li>
<li>Order editing will only store a Ship To address when STsameAsBT is empty.&nbsp; New order variable- STsameAsBT.&nbsp; With a new checkbox to control the addition of a ST address in order edit.</li>
</ul>
<p><strong>General</strong></p>
<ul>
<li>Added JRoute to action of the user edit form in FE.</li>
<li>Added filter vendors to user list.</li>
<li>Captcha for vendor contact form.</li>
<li>Shop configuration for FE views "set bootstrap layout version X", which adds a prefix for example bs2- for loaded layouts.</li>
<li>Added function "alt" to vmText.</li>
</ul>
<h1>Modifications</h1>
<ul>
<li>Important update for VirtueMart System plugin. It tries to load the configuration file of the installer and not the already installed one.</li>
<li>Exchanged hard coded string against vmText.</li>
<li>Spaces to Tab and indentation.</li>
<li>Replaced all id="vm. with id="vm-</li>
<li>Moved the js validation and setting the chosen dropdowns to required in an extra file.</li>
<li>Removed double id="reg_text", replaced with “class.</li>
<li>Language string change in de-DE.com_virtuemart_config.ini and en-GB.com_virtuemart_config.ini.</li>
<li>Removed mootools from vmbeez3 template.</li>
<li>Added plugintype vmextended to whitefilter of controllers/plugin.php.</li>
<li>Membership checker shows error in ajax request (simpler to debug).</li>
<li>Added JRoute to product link in sublayout products.</li>
<li>js using data-vm="product-container" instead of classes, fallback provided.</li>
<li>js now uses data-vm, all dependencies to classes will be removed soon. Fallbacks provided.</li>
<li>vmpsplugin.PHP methods which cannot be selected are now unset from the array of available methods.</li>
<li>vmplugin function _getLayoutPath is not public and static.</li>
<li>getMyOrderDetails, changed unused 3rd parameter. It sets now if the config should be considered for ordertracking. Some 3rd parties need it.</li>
<li>New fallback for product customfields, when the cart is loaded and had no data in the session.</li>
<li>Changed reload=1 attribute to data-reload=1 (with fallback in js for the old reload=1).</li>
<li>Changed activation text in registration email, when set to "activation by administrator".</li>
<li>prepareViewForMail now uses the generic controller, not a specific one (could make trouble with Admin and FE controllers having the same name).</li>
<li>Cart helper checkAutomaticSelectedPlug, when no method is available the method_id is set to 0.</li>
<li>Files of extensions are directly copied from temp directory to the correct place.</li>
<li>Product_name is now handled in the controller as the other special input fields and follows the ACL for writing raw/HTML or just normal text.</li>
<li>Added to getInstance of the calculationHelper the parameters vendorId, countryId and stateId.</li>
<li>Test for country/state improved. Added differentiation between valid and require.</li>
<li>Improve performance of calculationHelper function setCountryState using a new pattern to load the country and state of the registered user.</li>
<li>Added hidden feature, "directCheckout", which directly starts the checkout process with redirect</li>
</ul>
<h2>Fixes</h2>
<ul>
<li>URL of currency_converter/convertECB.php must use https now.</li>
<li>Cart object small fix which prevents overriding of $customProductData, when trigger plgVmOnAddToCartFilter is used.</li>
<li>Important fix for correct order status for order history.</li>
<li>Important fix for order editing was causing wrong calculation results - replaced product_item_price for product_discountedPriceWithoutTax for calculation of the subtotal.</li>
<li>Checkbox cartfields are now correctly stored in the order.</li>
<li>Fixed return value of function CreateOrderHead <a href="http://forum.virtuemart.net/index.php?topic=140616.0.">http://forum.virtuemart.net/index.php?topic=140616.0.</a></li>
<li>Added $view-&gt;mediaToSend = array(); in function sendVmMail to prevent sending of cached medias in order status update emails.</li>
<li>Fix for order_status vs order_status_code.</li>
<li>heidelpay, small fixes and changes.</li>
<li>PayPal hosted, fixed currency.</li>
<li>PayPal hosted payment iframe little catch for EMAILLINK – handles no PayPal response.</li>
<li>Standard payment: fix in tmpl.</li>
<li>Standard payment: update order status now happens before orderdone view rendering.</li>
<li>eway: fix the CVN in case of cart saved fix invoiceDescription.</li>
<li>authorize.net plgVmOnShowOrderFEPayment changed to public <a href="http://forum.virtuemart.net/index.php?topic=133563.msg492466#msg492466.">http://forum.virtuemart.net/index.php?topic=133563.msg492466#msg492466.</a></li>
<li>Fix in config.php typo in JLoader::register, creditcart.php to creditcard.php.</li>
<li>Correct storing of customplugins.</li>
<li>plgVmOnStoreInstallPluginTable of specification plugin.</li>
<li>Links to shoppergroups in ship-/payment methods listing.</li>
<li>Text in Virtuemart Search Module doesn't clear&nbsp; <a href="https://forum.virtuemart.net/index.php?topic=139961.0.">https://forum.virtuemart.net/index.php?topic=139961.0.</a></li>
<li>Small fix in admin product edit, which prevents removing the categories if a product is stored before the category tree was loaded.</li>
<li>Problem with not loaded parent categories in product detail.</li>
<li>Minor errors and typos (for example a note thrown cloning a product (thx Patrick K.).</li>
<li>Category cache.</li>
<li>Sublayout customfield used duplicate keys.</li>
<li>Corrected small typo in en-GB.mod_virtuemart_product.ini.</li>
<li>Fixed some Language translation issues.</li>
<li>Updated de-DE.mod_virtuemart_product.ini.</li>
<li>replaced JFactory::getLanguage against vmLanguage::getLanguage.</li>
</ul>
<h1>Minors</h1>
<ul>
<li>vmstore template foundation.</li>
<li>Added deletion of Media synchronization progress, when finished..</li>
<li>Removed old VM_VERSION (j2.5 compatibility).</li>
<li>Removed more DS, also for paths, added vRequest::filterPath().</li>
<li>Joomla Fullinstaller.</li>
<li>Removed unused files.</li>
<li>Replaced old JError against exception.</li>
<li>Added missing license notes.</li>
<li>Some old JRaiseError, JREQUEST_ALLOWHTML (also from old comments).</li>
<li>Removed unused error reporting(0); in Admin/views/orders/view.raw.php.</li>
<li>Removed old if !class_exists require's.</li>
<li>Install script.virtuemart.php removed old j1.6 legacy.</li>
<li>PayPal updated xml field to vmfile to vmfiles.</li>
<li>Updated vmbeez install file using the method "upgrade".</li>
<li>AIO installer replaced is_dir against JFolder::exists to prevent false positive error message.</li>
<li>Removed some more DS, the remaining DS are meant for realpath, which could be outside of the webfolder.</li>
<li>Textinput plugin, removed old trigger.</li>
<li>vmuploader, failing the joomla upload filter returns false.</li>
<li>Enhanced message when no vendor currency is defined.</li>
<li>Removed unnecessary language keys. Languages keys which are used in a special language file should never appear in the default component language file.</li>
<li>Package installer: added fallback in vmplugin to get the xml file from the default folder in case the temporary install folder fails.</li>
<li>Package installer: added check in updatesmigration.php if xml is available.</li>
<li>Use an internal variable html, to display the messages. It is echoed for normal install and put in the Request for package install.</li>
<li>Added template to package.</li>
<li>Updated js of the template.</li>
<li>Vendor list is now sending the form and ordered by name.</li>
<li>Changed function getUserList using user_is_vendor instead of is_vendor.</li>
</ul>
<p>TCPDF</p>
<ul>
<li>Moved tcpdf files to libraries/vendor/tecnickcom/ and libraries/src/Document following the new file structure of Joomla.</li>
<li>Now also deletes the old "libraries" folder in the be folder in case it exists.</li>
<li>Removed old j2.5, j1.6 stuff and DS.</li>
<li>Replaced DS for /.</li>
<li>Fixed wrong path in getTCPDFFontsList.</li>
<li>Fixed typo in vmpdf.</li>
</ul>
<h1>Work in progress</h1>
<ul>
<li>Vendor model another idea to handle a multicurrency store (would mean to create invoices in the user selected currency).</li>
<li>Better refund of invoices</li>
</ul>";}i:1;O:8:"stdClass":3:{s:4:"link";s:96:"https://virtuemart.net/news/489-virtuemart-3-2-14-security-release-and-enhanced-invoice-handling";s:5:"title";s:66:"VirtueMart 3.2.14 - Security Release and enhanced invoice handling";s:11:"description";s:2566:"<p>This fairly serious XSS discovered by Mattia Furlani pertained only the administration area, so most shops are not affected. Shop owners running a multi-vendor store or fearing that their employees may use this leak should update as soon as possible.</p>
<p>The new core has some fixes for php 7.1 - 7.2 compatibility.</p>
<h3>Compliance to the new french financial law</h3>
<p>At present we have also integrated some fraud protection requirements to comply with the new French law. This includes, for example, the new invoice processing system. When an invoice was changed, the old treatment renamed the originally created invoice and created a new invoice with the same invoice number. The new treatment creates a regular new invoice number while the old invoice remains listed and accessible. We also added an order item history table. The class vmtable can now automatically save a hash to any entry. For example the order entries store a hash of the important data per line, so it is now possible to check the integrity of an entry. This system is not completed yet.</p>
<h2>Further features:</h2>
<ul>
<li>Behaviour of the table object is more consistent and reliable.</li>
<li>Behaviour of payment plugins after pressing confirm in the cart and cancelling the payment is now more consistent.</li>
<li>Removed w3c validation errors.</li>
<li>Corrected routing for orderdone layout.</li>
<li>Trigger 'plgVmAfterStoreProduct', added array key "new" to $data, so that we know if a product is new or just updated.</li>
<li>Customfield date has now two extra parameters to set the initial date and year range. The initial date uses as format DateInterval, so the P0D means use the current.</li>
<li>Language files updated.</li>
<li>Long desired fix, dropdowns of prices in product edit work now directly.</li>
<li>Enhanced handling of the orderdone layout.</li>
<li>Minor compatibility enhancements of javascript and html.</li>
<li>_triesValidateCoupon is now emptied after entering a valid coupon.</li>
<li>Coupons are not automatically removed any longer when expired.</li>
<li>Full installer now also works with multilingual setup.</li>
</ul>
<p>The full list is available here <a href="http://forum.virtuemart.net/index.php?topic=139652.msg490664">http://forum.virtuemart.net/index.php?topic=139652.msg490664</a></p>
<div class="special-download">
<p style="text-align: center;"><a class="button-primary" href="https://virtuemart.net/download">DOWNLOAD VM3 NOW<br /> VirtueMart 3 component (core and AIO)</a></p>
<p style="text-align: center;"> </p>
</div>";}i:2;O:8:"stdClass":3:{s:4:"link";s:102:"https://virtuemart.net/news/488-virtuemart-and-the-new-french-financial-law-valid-since-january-1-2018";s:5:"title";s:71:"VirtueMart and the new french financial law valid since January 1, 2018";s:11:"description";s:453:"<p>The purpose of the french financial law n° <a href="https://www.legifrance.gouv.fr/eli/loi/2017/12/30/CPAX1723900L/jo/texte/#JORFARTI000036339313" target="_blank" rel="noopener noreferrer">2017-1837</a> is to combat VAT fraud. Since January 1, 2018, it obliges French ecommerce websites to use an extension that meets the requirements of inalterability, security, preservation and archiving of data for control of the french tax administration.</p>
";}i:3;O:8:"stdClass":3:{s:4:"link";s:52:"https://virtuemart.net/news/487-just-a-hotfix-update";s:5:"title";s:40:"VirtueMart 3.2.10 - Just a hotfix update";s:11:"description";s:912:"<p>Just a hotfix update.</p>
<div class="special-download">
<p style="text-align: center;"><a class="button-primary" href="https://virtuemart.net/download">DOWNLOAD VM3 NOW<br /> VirtueMart 3 component (core and AIO)</a></p>
<p style="text-align: center;"> </p>
</div>
<p>Here is the complete list of fixes:</p>
<ul>
<li>PayPal: Check IPN provider IP extra config parameter for standard and hosted (disabled by default now)</li>
<li>Important fix for vmcrypt preventing creation of keys, if there is already an existing one.</li>
<li>important fix for the date, the call was accidently using "null" as timezone parameter, which returns the server time. Added parameter and replaced the null against a default "false", which uses then the joomla configuration for the Timezone.</li>
<li>category browse view, added "alreadyLoadedIds" to group product for the feature "omitt already loaded"<br /><br /></li>
</ul>";}i:4;O:8:"stdClass":3:{s:4:"link";s:63:"https://virtuemart.net/news/486-virtuemart-3-2-8-bugfix-release";s:5:"title";s:33:"VirtueMart 3.2.8 - Bugfix Release";s:11:"description";s:2858:"<p>Unfortunately, we were a bit too fast with our security release, having found an error in the testing phase we created another small bug while we were fixing it.</p>
<p>VirtueMart usually sets the default Joomla frontend language as the shop language, it is this function that had an issue. Some multi-lingual shops failed to load products when the shop language was not explicitly set, or not by default in english.</p>
<p>We have tested this new fix and we do not see any bugs.</p>
<p>Finally, we dropped our dependency on SimplePie for RSS feeds and now use the JFeedFactory of Joomla to display the news and product feed on the dashboard.</p>
<div class="special-download">
<p style="text-align: center;"><a class="button-primary" href="https://virtuemart.net/download">DOWNLOAD VM3 NOW<br /> VirtueMart 3 component (core and AIO)</a></p>
</div>
<p>Here is the complete list of fixes:</p>
<ul>
<li>Fixes for search options and display of search results</li>
<li>Search plugin, added SKU (by Franz-Peter Scherer)</li>
<li>Shop language is correctly set by Joomla default front end language</li>
<li>Fixed another problem with the order language</li>
<li>While loop finding a product alias got increased to 40 (was 20) to prevent errors when child products did not find a proper alias</li>
<li>Fixed broken new Coupon</li>
<li>Fixed broken displayLogos function (was missing a DS)</li>
<li>Fixed version.php revision number</li>
<li>Fixed lost sorting of product list if a product was stored</li>
<li>Uncategorized products are listed again in the admin product list</li>
<li>The fixed thumbnail size in the product list is now set to 90px</li>
<li>Added layout of customfield to customfields list</li>
<li>vmLoaderPluginUpdate. Removed buggy isClient() against isAdmin(). So vm3.2.8 should be Joomla 2.5 compatible again</li>
<li>Browsing for products of a manufacturer now activates the subordinated settings analogous to categories</li>
<li>Removed links in Order print view (destroyed layouts without correct css)</li>
<li>Removed ShipTo address in invoice, if the address is the same as BillTo</li>
<li>Changed RSS feed, dropping simplepie and using jfeedFactory instead, see <a href="http://forum.virtuemart.net/index.php?topic=138918.msg487976#msg487976">http://forum.virtuemart.net/index.php?topic=138918.msg487976#msg487976</a></li>
<li>fancybox/jquery.fancybox-1.3.4.pack.js got updated. Removed a little bug. See <a href="https://forum.virtuemart.de/installation-updates-einrichtung-156/fancybox-fehlerverhalten-loesung-3146/">https://forum.virtuemart.de/installation-updates-einrichtung-156/fancybox-fehlerverhalten-loesung-3146/</a></li>
<li>Fix for the router when the URL of the product uses the language fallback</li>
<li>Fix XPF currency</li>
<li>Paybox: fix min_amount, countries and check server availability new parameter</li>
</ul>";}}s:6:"output";s:0:"";}