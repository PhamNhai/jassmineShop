<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

/**
* @package OS CCK
* @copyright 2016 OrdaSoft.
* @author Andrey Kvasnevskiy(akbet@mail.ru),Roman Akoev (akoevroman@gmail.com)
* @link http://ordasoft.com/cck-content-construction-kit-for-joomla.html
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @description OrdaSoft Content Construction Kit
*/

function print_vars($obj)
{
    $arr = get_object_vars($obj);
    while (list($prop, $val) = each($arr))
        if (class_exists($val)) print_vars($val);
        else echo "\t $prop = $val\n<br />";
}

function print_methods($obj)
{
    $arr = get_class_methods(get_class($obj));
    foreach ($arr as $method) echo "\tfunction $method()\n <br />";
}

if (PHP_VERSION >= 5) {
    // Emulate the old xslt library functions
    function xslt_create()
    {
        return new XsltProcessor();
    }

    function xslt_process($xsltproc,
                          $xml_arg,
                          $xsl_arg,
                          $xslcontainer = null,
                          $args = null,
                          $params = null)
    {

        // Create instances of the DomDocument class
        $xml = new DomDocument;
        $xsl = new DomDocument;

        // Load the xml document and the xsl template
        $xml->load($xml_arg);
        $xsl->load($xsl_arg);

        // Load the xsl template
        $xsltproc->importStyleSheet($xsl);

        // Set parameters when defined
        if ($params) {
            foreach ($params as $param => $value) {
                $xsltproc->setParameter("", $param, $value);
            }
        }

        // Start the transformation
        $processed = $xsltproc->transformToXML($xml);

        // Put the result in a file when specified
        if ($xslcontainer) {
            return @file_put_contents($xslcontainer, $processed);
        } else {
            return $processed;
        }

    }

    function xslt_free($xsltproc)
    {
        unset($xsltproc);
    }
}

class mosOS_CCKImportExport
{


    /**
     * Imports the lines given to this method into the database and writes a
     * table containing the information of the imported houses.
     * The imported houses will be set to [not published]
     * Format: #;id;isbn;title;author;language
     * @param array lines - an array of lines read from the file
     * @param int catid - the id of the category the houses should be added to
     */
    function importItemsCSV($lines, $catid)
    {
        global $database;
        $retVal = array();
        $i = 0;
        foreach ($lines as $line) {
            $tmp = array();

            if (trim($line) == "") continue;
            $line = explode('|', $line); //edit nik  old -->, -1);
            $house = new mosOS_CCK($database);
            $house->itemid = trim($line[0]);
            $house->description = $line[1];
            $house->link = $line[2];
            $house->listing_type = $line[3];
            $house->price = $line[4];
            $house->price_type = $line[5];
            $house->htitle = $line[6];

            $house->hlocation = $line[7];
            $house->hlatitude = $line[8];
            $house->hlongitude = $line[9];

            $house->bathrooms = $line[10];
            $house->bedrooms = $line[11];
            $house->broker = $line[12];
            $house->image_link = $line[13];
            $house->listing_status = $line[14];

            $house->property_type = $line[15];
            $house->provider_class = $line[16];
            $house->year = $line[17];

            $house->agent = $line[18];
            $house->area = $line[19];
            $house->expiration_date = $line[20];
            $house->feature = $line[21];
            $house->hoa_dues = $line[22];
            $house->lot_size = $line[23];
            $house->model = $line[24];
            $house->property_taxes = $line[25];
            $house->school = $line[26];
            $house->school_district = $line[27];
            $house->style = $line[28];
            $house->zoning = $line[29];
            $house->date = $line[30];
            $house->hits = $line[31];

            $house->catid = $catid;


            // optimize!!!
            $tmp[0] = $i;
            $tmp[1] = trim($line[0]);
//			            $tmp[2] = $line[12];
            $tmp[2] = $line[7];
            $tmp[3] = $line[6];
            $tmp[4] = $line[3];

            if (!$house->check() || !$house->store()) {
                $tmp[5] = $house->getError();
            } else {
                $tmp[5] = "OK";
            }
            $house->checkin();
            $house->updateOrder("catid='$house->catid'");
            $retVal[$i] = $tmp;
            $i++;
        }
        return $retVal;
    }

    function getXMLItemValue($item, $item_name)
    {
        $house_items = $item->getElementsByTagname($item_name);
        $house_item = $house_items->item(0);
        if (NULL != $house_item) return $house_item->nodeValue;
        else return "";
    }

    function findCategory(& $categories, $new_category)
    {
        global $database;

        foreach ($categories as $category) {
            if ($category->old_id == $new_category->old_id) return $category;
        }
        $new_parent_id = null;
        if ($new_category->old_parent_id != 0) {
            foreach ($categories as $category) {
                if ($category->old_id == $new_category->old_parent_id) {
                    $new_parent_id = $category->id;
                    break;
                }
            }
        } else $new_parent_id = 0;

        //sanity test
        if ($new_parent_id === null) {
            echo "error in import !";
            exit;
        }

        $row = new os_cckCategory($database);
        $row->section = 'com_os_cck';
        $row->parent_id = $new_parent_id;
        $row->name = $new_category->name;
        $row->title = $new_category->title;
        $row->published = $new_category->published;

        if (!$row->check()) {
            echo "error in import2 !";
            exit;
            exit();
        }
        if (!$row->store()) {
            echo "error in import3 !";
            exit;
            exit();
        }

        $row->updateOrder("section='com_os_cck' AND parent_id='$row->parent_id'");

        $new_category->id = $row->id;
        $categories[] = $new_category;

        return $new_category;

    }

//******************   begin add for import XML format   ****************************
    function importItemsXML($files_name_pars, $catid)
    {

        global $database;
        $retVal = array();
        $k = 0;
        $new_categories = array();
        $new_relate_ids = array();

        $dom = new domDocument('1.0', 'windows-1251');
        $dom->load($files_name_pars);

        if ($catid === null) {
            $cat_list = $dom->getElementsByTagname('category');
            for ($i = 0; $i < $cat_list->length; $i++) {
                $category = $cat_list->item($i);
                $new_category = null;
                $new_category->old_id = mosOS_CCKImportExport::getXMLItemValue($category, 'category_id');
                $new_category->old_parent_id = mosOS_CCKImportExport::getXMLItemValue($category, 'category_parent_id');
                $new_category->name = mosOS_CCKImportExport::getXMLItemValue($category, 'category_name');
                $new_category->title = mosOS_CCKImportExport::getXMLItemValue($category, 'category_title');
                $new_category->published = mosOS_CCKImportExport::getXMLItemValue($category, 'category_published');
                $new_category->params = mosOS_CCKImportExport::getXMLItemValue($category, 'category_type');
                $new_category = mosOS_CCKImportExport::findCategory($new_categories, $new_category);
            }
        }


        $house_list = $dom->getElementsByTagname('house');
        for ($i = 0; $i < $house_list->length; $i++) {
            $house_class = new mosOS_CCK($database);

            $house = $house_list->item($i);

            //get ItemID
            $item_id = $house_class->itemid = mosOS_CCKImportExport::getXMLItemValue($house, 'itemid');
//getting requirement fields
            //gering rent ID
            $house_class->fk_rentid = mosOS_CCKImportExport::getXMLItemValue($house, 'fk_rentid');
            //get description
            $house_description = $house_class->description = mosOS_CCKImportExport::getXMLItemValue($house, 'description');
            //get link
            $house_class->link = mosOS_CCKImportExport::getXMLItemValue($house, 'link');
            //get listing_type
            $house_listing_type = $house_class->listing_type = mosOS_CCKImportExport::getXMLItemValue($house, 'listing_type');
            //get Title(house)
            $house_htitle = $house_class->htitle = mosOS_CCKImportExport::getXMLItemValue($house, 'htitle');
            //get price
            $house_class->price = mosOS_CCKImportExport::getXMLItemValue($house, 'price');
//getting recommended fields
            //get location
            $house_hlocation = $house_class->hlocation = mosOS_CCKImportExport::getXMLItemValue($house, 'hlocation');
            //get latitude
            $house_hlatitude = $house_class->hlatitude = mosOS_CCKImportExport::getXMLItemValue($house, 'hlatitude');
            //get longitude
            $house_hlongitude = $house_class->hlongitude = mosOS_CCKImportExport::getXMLItemValue($house, 'hlongitude');
            //get map_zoom
//		    $house_map_zoom = $house_class->map_zoom = mosOS_CCKImportExport::getXMLItemValue($house,'map_zoom');
            //get bathrooms
            $house_class->bathrooms = mosOS_CCKImportExport::getXMLItemValue($house, 'bathrooms');
            //get bedrooms
            $house_class->bedrooms = mosOS_CCKImportExport::getXMLItemValue($house, 'bedrooms');
            //get broker
            $house_class->broker = mosOS_CCKImportExport::getXMLItemValue($house, 'broker');
            //get image_link
            $house_class->image_link = mosOS_CCKImportExport::getXMLItemValue($house, 'image_link');
            //get listing_status
            $house_class->listing_status = mosOS_CCKImportExport::getXMLItemValue($house, 'listing_status');


            //get price_type
            $house_class->price_type = mosOS_CCKImportExport::getXMLItemValue($house, 'price_type');
            //get property_type
            $house_class->property_type = mosOS_CCKImportExport::getXMLItemValue($house, 'property_type');
            //get provider_class
            $house_class->provider_class = mosOS_CCKImportExport::getXMLItemValue($house, 'provider_class');
            //get year
            $house_class->year = mosOS_CCKImportExport::getXMLItemValue($house, 'year');
//getting optional fields
            //get agent
            $house_class->agent = mosOS_CCKImportExport::getXMLItemValue($house, 'agent');
            //get area
            $house_class->area = mosOS_CCKImportExport::getXMLItemValue($house, 'area');
            //get expiration_date
            $house_class->expiration_date = mosOS_CCKImportExport::getXMLItemValue($house, 'expiration_date');
            //get feature
            $house_class->feature = mosOS_CCKImportExport::getXMLItemValue($house, 'feature');
            //get hoa_dues
            $house_class->hoa_dues = mosOS_CCKImportExport::getXMLItemValue($house, 'hoa_dues');
            //get lot_size
            $house_class->lot_size = mosOS_CCKImportExport::getXMLItemValue($house, 'lot_size');
            //get model
            $house_class->model = mosOS_CCKImportExport::getXMLItemValue($house, 'model');
            //get property_taxes
            $house_class->property_taxes = mosOS_CCKImportExport::getXMLItemValue($house, 'property_taxes');
            //get school
            $house_class->school = mosOS_CCKImportExport::getXMLItemValue($house, 'school');
            //get school_district
            $house_class->school_district = mosOS_CCKImportExport::getXMLItemValue($house, 'school_district');
            //get style
            $house_class->style = mosOS_CCKImportExport::getXMLItemValue($house, 'style');
            //get zoning
            $house_class->zoning = mosOS_CCKImportExport::getXMLItemValue($house, 'zoning');

            //get hits
            $house_class->hits = mosOS_CCKImportExport::getXMLItemValue($house, 'hits');
            //get date
            $house_class->date = mosOS_CCKImportExport::getXMLItemValue($house, 'date');
            //get published
            $house_class->published = mosOS_CCKImportExport::getXMLItemValue($house, 'published');

            //get contacts
            $house_class->contacts = mosOS_CCKImportExport::getXMLItemValue($house, 'contacts');
            //get category

            if ($catid === null) {
                $new_category = null;
                $new_category->old_id = mosOS_CCKImportExport::getXMLItemValue($house, 'catid');

                $new_category = mosOS_CCKImportExport::findCategory($new_categories, $new_category);

                //set category
                $house_class->catid = $new_category->id;
            } else {
                $new_category = new mosCategory($database);
                $new_category->Load($catid);

                $house_class->catid = $catid;
            }

            //for output rezult in table
            $tmp[0] = $i;
            $tmp[1] = $item_id;
            $tmp[2] = $house_hlocation;
            $tmp[3] = $house_htitle;
            $tmp[4] = $house_class->broker;


//ПРоверяется наличие дома с указанным id и 
            if (!$house_class->check() || !$house_class->store()) {
                $tmp[5] = $house_class->getError();
            } else {
                $tmp[5] = "OK";
            }

            $house_class->checkin();
            $house_class->updateOrder("catid='$house_class->catid'");
            $retVal[$i] = $tmp;


            //get Reviews
            if ($tmp[5] = "OK" && mosOS_CCKImportExport::getXMLItemValue($house, 'reviews') != "") {
                $review_list = $house->getElementsByTagname('review');
                for ($j = 0; $j < $review_list->length; $j++) {

                    $review = $review_list->item($j);

                    //get for review - user_name
                    $review_user_name = mosOS_CCKImportExport::getXMLItemValue($review, 'user_name');
                    //get for review - user_email
                    $review_user_email = mosOS_CCKImportExport::getXMLItemValue($review, 'user_email');
                    //get for review - date
                    $review_date = mosOS_CCKImportExport::getXMLItemValue($review, 'date');
                    //get for review - rating
                    $review_rating = mosOS_CCKImportExport::getXMLItemValue($review, 'rating');
                    //get for review - title
                    $review_title = mosOS_CCKImportExport::getXMLItemValue($review, 'title');
                    //get for review - comment
                    $review_comment = mosOS_CCKImportExport::getXMLItemValue($review, 'comment');

                    //insert data in table #__os_cck_review
                    $database->setQuery("INSERT INTO #__os_cck_review" .
                    "\n (fk_itemid, user_name,user_email, date, rating, title, comment)" .
                    "\n VALUES " .
                    "\n (" . $house_class->id . ", '" . $review_user_name . "', '" . $review_user_email .
                    "', '" . $review_date . "'," . $review_rating . ",'" . $review_title . "', '" . $review_comment . "');");
                    $database->query();

                } //end for(...) - REVIEW
            } //end if(...) - REVIEW


            //get rents
            if ($tmp[5] = "OK" && mosOS_CCKImportExport::getXMLItemValue($house, 'rents') != "") {
                $rent_list = $house->getElementsByTagname('rent');
                for ($j = 0; $j < $rent_list->length; $j++) {

                    $rent = $rent_list->item($j);

                    $help = new mosOS_CCK_rent($database);

                    $help->fk_itemid = $house_class->itemid;
                    //get for rent - rent_from
                    $help->rent_from = mosOS_CCKImportExport::getXMLItemValue($rent, 'rent_from');
                    //get for rent - rent_until
                    $help->rent_until = mosOS_CCKImportExport::getXMLItemValue($rent, 'rent_until');
                    //get for rent - rent_return
                    $rent_return = mosOS_CCKImportExport::getXMLItemValue($rent, 'rent_return');
                    //get for rent - user_name
                    $help->user_name = mosOS_CCKImportExport::getXMLItemValue($rent, 'user_name');
                    //get for rent - user_email
                    $help->user_email = mosOS_CCKImportExport::getXMLItemValue($rent, 'user_email');
                    //get for rent - user_mailing
                    $help->user_mailing = mosOS_CCKImportExport::getXMLItemValue($rent, 'user_mailing');

                    //insert data in table #__os_cck_rent
                    if (empty($rent_return)) {
                        $help->rent_return = NULL;

                    } else {
                        $help->rent_return = $rent_return;
                    }

                    if (!$help->check() || !$help->store()) {
                        $tmp[5] = $help->getError();
                    } else {

                        if (!$house_class->check() || !$house_class->store()) {
                            $tmp[5] = $house_class->getError();
                        } else {
                            $tmp[5] = "OK";
                        }
                    }

                } //end for(...) - rent
            } //end if(...) - rent

            //get rentrequests
            if ($tmp[5] = "OK" && mosOS_CCKImportExport::getXMLItemValue($house, 'rentrequests') != "") {
                $rentrequests_list = $house->getElementsByTagname('rentrequest');
                for ($j = 0; $j < $rentrequests_list->length; $j++) {

                    $rentrequest = $rentrequests_list->item($j);

                    //get for rentrequest - rent_from
                    $rr_rent_from = mosOS_CCKImportExport::getXMLItemValue($rentrequest, 'rent_from');
                    //get for rentrequest - rent_until
                    $rr_rent_until = mosOS_CCKImportExport::getXMLItemValue($rentrequest, 'rent_until');
                    //get for rentrequest - rent_return
                    $rr_rent_request = mosOS_CCKImportExport::getXMLItemValue($rentrequest, 'rent_request');
                    //get for rentrequest - user_name
                    $rr_user_name = mosOS_CCKImportExport::getXMLItemValue($rentrequest, 'user_name');
                    //get for rentrequest - user_email
                    $rr_user_email = mosOS_CCKImportExport::getXMLItemValue($rentrequest, 'user_email');
                    //get for rentrequest - user_mailing
                    $rr_user_mailing = mosOS_CCKImportExport::getXMLItemValue($rentrequest, 'user_mailing');
                    //get for rentrequest - status
                    $rr_status = mosOS_CCKImportExport::getXMLItemValue($rentrequest, 'status');

                    //insert data in table #__os_cck_rent_request
                    $database->setQuery("INSERT INTO #__os_cck_rent_request " .
                    "\n (fk_itemid, rent_from,rent_until, rent_request, user_name, user_email, user_mailing,status)" .
                    "\n VALUES " .
                    "\n (" . $house_class->id . ", '" . $rr_rent_from . "', '" . $rr_rent_until .
                    "', '" . $rr_rent_request . "','" . $rr_user_name . "','" . $rr_user_email . "', '" . $rr_user_mailing .
                    "', '" . $rr_status . "');");
                    $database->query();

                } //end for(...) - rentrequest
            } //end if(...) - rentrequest

            //get buyingrequests
            if ($tmp[5] = "OK" && mosOS_CCKImportExport::getXMLItemValue($house, 'buyingrequests') != "") {
                $buyingrequests_list = $house->getElementsByTagname('buyingrequest');
                for ($j = 0; $j < $buyingrequests_list->length; $j++) {

                    $buyingrequest = $buyingrequests_list->item($j);

                    //get for $buyingrequest - buying_request
                    $br_buying_request = mosOS_CCKImportExport::getXMLItemValue($buyingrequest, 'buying_request');
                    //get for $buyingrequest - customer_name
                    $br_customer_name = mosOS_CCKImportExport::getXMLItemValue($buyingrequest, 'customer_name');
                    //get for $buyingrequest - customer_email
                    $br_customer_email = mosOS_CCKImportExport::getXMLItemValue($buyingrequest, 'customer_email');
                    //get for $buyingrequest - customer_phone
                    $br_customer_phone = mosOS_CCKImportExport::getXMLItemValue($buyingrequest, 'customer_phone');
                    //get for $buyingrequest - status
                    $br_status = mosOS_CCKImportExport::getXMLItemValue($buyingrequest, 'status');

                    //insert data in table #__os_cck_buying_request
                    $database->setQuery("INSERT INTO #__os_cck_buying_request " .
                    "\n (fk_itemid, buying_request, customer_name, customer_email, customer_phone,status)" .
                    "\n VALUES " .
                    "\n (" . $house_class->id .
                    ", '" . $br_buying_request . "','" . $br_customer_name . "','" . $br_customer_email . "', '" . $br_customer_phone .
                    "', '" . $br_status . "');");
                    $database->query();

                } //end for(...) - $buyingrequest
            } //end if(...) - $buyingrequest
        }
        //end for(...) - house

        return $retVal;
    }

//***************************************************************************************************
//***********************   end add for import XML format   *****************************************
//***************************************************************************************************

    function exportItemsXML($houses, $all)
    {
        global $mosConfig_live_site, $mosConfig_absolute_path, $os_cck_configuration, $database;

        $xmlDoc = new DOMIT_Document();
        $strXmlDoc = "";
        $strXmlDoc .= "<?xml version='1.0' encoding='iso-8859-2'?>\n";

        $strXmlDoc .= "<houses_data>\n";
        if ($all) {

            //create and append list element
            $categories_dom = $xmlDoc->createElement("categories");
            $strXmlDoc .= "<categories>\n";

            $database->setQuery("SELECT name, title,section, id, parent_id, published FROM #__categories " .
            "WHERE section='com_os_cck' order by parent_id ; ");
            $categories = $database->loadObjectList();

            foreach ($categories as $category) {
                //add category
                $category_dom = $xmlDoc->createElement("category");

                $category_id = $xmlDoc->createElement("category_id");
                $category_id->appendChild($xmlDoc->createTextNode($category->id));
                $category_dom->appendChild($category_id);

                $category_parent_id = $xmlDoc->createElement("category_parent_id");
                $category_parent_id->appendChild($xmlDoc->createTextNode($category->parent_id));
                $category_dom->appendChild($category_parent_id);

                $category_name = $xmlDoc->createElement("category_name");
                $category_name->appendChild($xmlDoc->createCDATASection($category->name));
                $category_dom->appendChild($category_name);

                $category_title = $xmlDoc->createElement("category_title");
                $category_title->appendChild($xmlDoc->createCDATASection($category->title));
                $category_dom->appendChild($category_title);

                $category_section = $xmlDoc->createElement("category_section");
                $category_section->appendChild($xmlDoc->createTextNode($category->section));
                $category_dom->appendChild($category_section);

                $category_published = $xmlDoc->createElement("category_published");
                $category_published->appendChild($xmlDoc->createTextNode($category->published));
                $category_dom->appendChild($category_published);

                //$categories_dom->appendChild( $category_dom);
                $strXmlDoc .= $category_dom->toNormalizedString();
            }
            //create and append list element
            $strXmlDoc .= "</categories>\n";

        }

        //create and append list element
        $strXmlDoc .= "<houses_list>\n";
        foreach ($houses as $house) {
            $strXmlDoc .= $house->toXML2($all);
        }
        $strXmlDoc .= "</houses_list>\n";

        $strXmlDoc .= "</houses_data>";

        return $strXmlDoc;

    }

    function storeExportFile($data, $type)
    {
        global $mosConfig_live_site, $mosConfig_absolute_path, $os_cck_configuration;
        $fileName = "os_cck_" . date("Ymd_His");
        $fileBase = "/administrator/components/com_os_cck/exports/";

        //write the xml file
        $fp = fopen($mosConfig_absolute_path . $fileBase . $fileName . ".xml", "w", 0); #open for writing

        fwrite($fp, $data); #write all of $data to our opened file
        fclose($fp); #close the file


        $InformationArray = array();
        $InformationArray['xml_file'] = $fileName . '.xml';
        $InformationArray['log_file'] = $fileName . '.log';
        $InformationArray['fileBase'] = "file://" . getcwd() . "/components/com_os_cck/exports/";
        $InformationArray['urlBase'] = $mosConfig_live_site . $fileBase;
        $InformationArray['out_file'] = $InformationArray['xml_file'];
        $InformationArray['error'] = null;

        switch ($type) {
            case 'csv':
                $InformationArray['xslt_file'] = 'csv.xsl';
                $InformationArray['out_file'] = $fileName . '.csv';
                mosOS_CCKImportExport :: transformPHP4($InformationArray);
                break;

            default:
                break;
        }

        return $InformationArray;
    }


    function transformPHP4(&$InformationArray)
    {
        // create the XSLT processor^M
        $xh = xslt_create() or die("Could not create XSLT processor");


        // Process the document
        $result = xslt_process($xh, $InformationArray['fileBase'] . $InformationArray['xml_file'],
            $InformationArray['fileBase'] . $InformationArray['xslt_file'],
            $InformationArray['fileBase'] . $InformationArray['out_file']);

        if (!$result) {
            // Something croaked. Show the error
            $InformationArray['error'] = "Cannot process XSLT document: " . /*xslt_errno($xh) .*/
                " " /*. xslt_error($xh)*/
            ;
        }

        // Destroy the XSLT processor
        xslt_free($xh);

    }

//////////  MY  ///////////////////////////////////////////////

    function remove_info()
    {
        global $database;
        $database->setQuery('truncate #__os_cck_items');
        $database->query();
        if ($database->getErrorNum()) {
            echo $database->stderr();
            return $database->stderr();
        }
        $database->setQuery("delete from #__categories where section='com_os_cck'");
        $database->query();
        if ($database->getErrorNum()) {
            echo $database->stderr();
            return $database->stderr();
        }
        $database->setQuery('truncate #__os_cck_review');
        $database->query();
        if ($database->getErrorNum()) {
            echo $database->stderr();
            return $database->stderr();
        }
        $database->setQuery('truncate #__os_cck_rent');
        $database->query();
        if ($database->getErrorNum()) {
            echo $database->stderr();
            return $database->stderr();
        }
        $database->setQuery('truncate #__os_cck_rent_request');
        $database->query();
        if ($database->getErrorNum()) {
            echo $database->stderr();
            return $database->stderr();
        }
        $database->setQuery('truncate #__os_cck_buying_request');
        $database->query();
        if ($database->getErrorNum()) {
            echo $database->stderr();
            return $database->stderr();
        }
        return "";
    }
}

?>
