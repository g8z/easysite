<?php

class Report extends DB_Item
{

    // mode = html|pdf
    var $mode = 'html';
    var $content ='';
    var $numSubmissions;
    var $nav;
    var $conditions;

    var $_fieldReplace = array();
    var $_embedTypes = array();
    var $_customFields = array();
    var $_embedded;
    var $_embeddedCondition = array();

    // contains the bounds of groups
    var $groupArray = array();
    var $groups = array();

    var $settings;
    var $overrideConditions;

    function Report( $id=0 ) {

    	$this->DB_Item( $id, REPORTS_TABLE );

	$this->resource_type = 'report';

        // init child classes for cascade deletion
        include_once( INCLUDE_DIR . 'internal/db_items/class.Report_Condition.php' );
        include_once( INCLUDE_DIR . 'internal/db_items/class.Report_Group.php' );
        include_once( INCLUDE_DIR . 'internal/db_items/class.Report_Field.php' );
        $this->_childClass[] = new Report_Condition();
        $this->_childClass[] = new Report_Group();
        $this->_childClass[] = new Report_Field();
        $this->_parentField['form'] = 'resource';

        $this->_fieldReplace = array(
            USERS_TABLE => array(
				'id' 		    => array( 'label'=>'Unique ID',       'filterOutput'=>0 ),
				'login_id' 		=> array( 'label'=>'Login ID',        'filterOutput'=>0 ),
				'login_pass'	=> array( 'label'=>'Password',        'filterOutput'=>0 ),
				'first_name'	=> array( 'label'=>'First Name',      'filterOutput'=>0 ),
				'last_name'		=> array( 'label'=>'Last Name',       'filterOutput'=>0 ),
				'email'			=> array( 'label'=>'E-Mail',          'filterOutput'=>1 ),
				'url'			=> array( 'label'=>'URL',             'filterOutput'=>1 ),
				'phone'			=> array( 'label'=>'Phone',           'filterOutput'=>0 ),
				'address_1'		=> array( 'label'=>'Address, Line 1', 'filterOutput'=>0 ),
				'address_2'		=> array( 'label'=>'Address, Line 2', 'filterOutput'=>0 ),
				'group_id'		=> array( 'label'=>'Group',           'filterOutput'=>1 ),
				'comments'		=> array( 'label'=>'Comments',        'filterOutput'=>0 ),
				'date_created'	=> array( 'label'=>'Creation Date',   'filterOutput'=>0 ),
				'last_login'	=> array( 'label'=>'Last Login Date', 'filterOutput'=>0 ),
				'date_expires' 	=> array( 'label'=>'Expiration Date', 'filterOutput'=>0 ),
				'use_expiration'=> array( 'label'=>'Expires?',        'filterOutput'=>1 ),
				'user_site_key'	=> array( 'label'=>'User Site',       'filterOutput'=>1 ),
				'status'		=> array( 'label'=>'Status',          'filterOutput'=>0 ),
				'company'		=> array( 'label'=>'Company/Organization', 'filterOutput'=>0 ),
				'fax'			=> array( 'label'=>'Fax',             'filterOutput'=>0 ),
				'city'			=> array( 'label'=>'City',            'filterOutput'=>0 ),
				'state'			=> array( 'label'=>'State',           'filterOutput'=>0 ),
				'zip'			=> array( 'label'=>'Zip',             'filterOutput'=>0 ),
				'country'		=> array( 'label'=>'Country',         'filterOutput'=>0 ),
				'member_id'		=> array( 'label'=>'Member ID',       'filterOutput'=>0 ),
            ),
            DB_PREFIX.'_gallery_orders' => array(
				'id' 		    => array( 'label'=>'Unique ID',       'filterOutput'=>0 ),
				'total_amount' 		=> array( 'label'=>'Total Amount',        'filterOutput'=>0 ),
				'tax'	=> array( 'label'=>'Tax',        'filterOutput'=>0 ),
				'discount'	=> array( 'label'=>'Discount',      'filterOutput'=>0 ),
				'creation_date'	=> array( 'label'=>'Created',      'filterOutput'=>0 ),
				'country'	=> array( 'label'=>'Ship. Country',      'filterOutput'=>0 ),
				'state'	=> array( 'label'=>'Ship. State',      'filterOutput'=>0 ),
				'city'	=> array( 'label'=>'Ship. City',      'filterOutput'=>0 ),
				'address_1'	=> array( 'label'=>'Ship. Address, Line 1',      'filterOutput'=>0 ),
				'address_2'	=> array( 'label'=>'Ship. Address, Line 2',      'filterOutput'=>0 ),
				'zip'	=> array( 'label'=>'Ship. Zip',      'filterOutput'=>0 ),
				'shipping_method'	=> array( 'label'=>'Shipping Method',      'filterOutput'=>1 ),
				'payment_method'	=> array( 'label'=>'Payment Method',      'filterOutput'=>0 ),
				'status'	=> array( 'label'=>'Status',      'filterOutput'=>0 ),
            ),
            DB_PREFIX.'_gallery_order_contents' => array(
				'id' 		    => array( 'label'=>'Unique ID',       'filterOutput'=>0 ),
				'item_title' 		=> array( 'label'=>'Item Title',        'filterOutput'=>0 ),
				'item_price'	=> array( 'label'=>'Item Price',        'filterOutput'=>0 ),
            ),
        );

        $this->_embedTypes = array(
        	USERS_TABLE                 => array( SITES_TABLE=>array( 'title'=>'Sites', 'key_p'=>'id', 'key_e'=>'admin_id' ) ),
        	DB_PREFIX.'_gallery_orders' => array( DB_PREFIX.'_gallery_order_contents' => array( 'title'=>'Order Contents', 'key_p'=>'id', 'key_e'=>'order_id' ) ),
        );

        $this->overrideConditions = false;
        $this->conditions = array();

        $this->_embeddedCondition = array();

        $this->_customFields = array();


    }


    function getEmbeddedTypes() {

    	$types = array();

    	if ( !is_numeric( $this->fields['resource'] ) )
    		// get related tables
    		$types = $this->_embedTypes[$this->fields['resource']];
    	else {

    		// get conditional redirected forms as resources for embedded reports
    		$t = $this->_db->getAll( '
    			select
    				s.value as title,
    				fr.redirect_id as id
    			from '. FORMS_TABLE ." f
    			left join ".FORMREDIRECTS_TABLE." fr on f.id=fr.form_id
    			left join ". SETTINGS_TABLE." s on s.resource_id=fr.redirect_id and s.resource_type='form' and s.property='title'
    			where f.id='{$this->fields[resource]}' and f.redirect_type='condition' and fr.redirect_type='form'" );

    		$types = array();

    		foreach ( $t as $idx=>$item ) {
    			$types[$item[id]] = array( 'title'=>'Form - '.$item['title'], 'key_p'=>'submission_id' );
    		}
    	}

    	return $types;
    }


    function getSettings( $id ) {

        global $db, $site;

        $settings = array();

        $sets = $db->getAll( 'select property, value from '. SETTINGS_TABLE . " where resource_type='report' and resource_id='$id'" );

        foreach ( $sets as $num=>$set ) {
            $settings[$set[property]] = $set[value];
        }

        $this->settings = $settings;

        return $settings;
    }


    function _compare( $val1, $val2, $mode='num' ) {
        //$mode = 'num';

        if ( $mode != 'num' ) {

          // add character to make values strings
          $val1 = '_' . $val1;
          $val2 = '_' . $val2;
        }

        if ( $val1 < $val2 ) $rez = -1;
        if ( $val1 == $val2 ) $rez = 0;
        if ( $val1 > $val2 ) $rez = 1;

        return $rez;

    }

    function qsort_multiarray( $array, $num = 0, $order = 'ASC', $mode='num', $left = 0, $right = -1 ) {

        if ( $right == -1 )
            $right = count($array) - 1;

        $l = $left;
        $r = $right;
        $m = $array[($left + $right) / 2][$num];



        if ( $r > $l ) {
            do
            {
                if($order == "ASC") {

                    while( $this->_compare($array[$l][$num], $m, $mode) < 0 ) $l++;
                    while( $this->_compare($array[$r][$num], $m, $mode) > 0 ) $r--;

                }
                else {

                    while( $this->_compare($array[$l][$num], $m, $mode) > 0 ) $l++;
                    while( $this->_compare($array[$r][$num], $m, $mode) < 0 ) $r--;

                }

                if ( $l <= $r ) {

                    $tmp = $array[$l];
                    $array[$l++] = $array[$r];
                    $array[$r--] = $tmp;
                }

            } while($l <= $r);

            if ($left < $r) $array = $this->qsort_multiarray( $array, $num, $order, $mode, $left, $r);
            if ($l < $right) $array = $this->qsort_multiarray( $array, $num, $order, $mode, $l, $right);
        }



        return $array;
     }


    function generate( $id ) {
        if ( $this->mode == 'html' )
            return $this->_generateHTML( $id );
        else
            return $this->_generatePDF( $id );
    }


    function _generateHTML( $id ) {

        global $db, $t;

        if ( !$this->exists( $id ) )
            return false;

        include_once( INCLUDE_DIR . 'internal/db_items/class.Submission.php' );
        include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Redirect.php' );
        include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Section.php' );
        include_once( INCLUDE_DIR . 'internal/db_items/class.Report_Group.php' );
        include_once( INCLUDE_DIR . 'internal/db_items/class.Report_Field.php' );
        include_once( INCLUDE_DIR . 'internal/db_items/conditionFunctions.php' );

        require_once $t->_get_plugin_filepath('function', 'imgsrc');

        $fSubm  = new Submission();
        $fSect  = new Form_Section();
        $fRedir = new Form_Redirect();
        $rGroup = new Report_Group();
        $rField = new Report_Field();

        $this->load( $id );

        $site = $this->fields['site_key'];

        $this->getSettings( $id );

        if ( !$this->overrideConditions ) {
            $rCond = new Report_Condition();
            $conds = $rCond->loadByParentId( $this->fields[id], array( 'id', 'condition', 'case_sen', 'value', 'section_id' ), 'report' );
            $this->conditions = $conds;
        }
        unset( $rCond );

        // check if _embeddedCondition should override any existing

        if ( @count( $this->_embeddedCondition ) )
	        if ( is_array( $this->conditions ) && count( $this->conditions ) ) {
		        foreach ( $this->conditions as $idx=>$item ) {
		        	if ( $item['section_id'] == $this->_embeddedCondition['section_id'] )
		        		$this->condition[$idx] = $this->_embeddedCondition;
		        }
	        }
	        else {
	        	$this->conditions[] = $this->_embeddedCondition;
	        }

        if ( is_numeric( $this->fields[resource] ) ) {

            $subIds = $fSubm->loadColumnCond( 'submission_id', array( 'form_id'=>$this->fields[resource], 'site_key'=>$site ), '', 'group by submission_id' );
        }
        else {

            $subIds = array();
            $tableIds = $db->getAll( 'select id from '. $this->fields[resource] . " where site_key='$site'" );

            foreach( $tableIds as $num => $tableId )
                $subIds[] = $tableId['id'];

        }

        // ---------------------------------------------
        // check if this report contain embedded reports
        // ---------------------------------------------

        $callEmbed = array();
        $embedded = $this->_db->getAll( 'select r.name, r.id, r.resource from '. EMBEDDEDREPORTS_TABLE.' e left join '. REPORTS_TABLE." r on e.source_id=r.id where e.into_id='$id' and e.site_key='$site'" );
        if ( is_array( $embedded ) && count( $embedded ) )
        foreach ( $embedded as $idx=>$item ) {

        	// does not working witout 'a'

        	$a = $this->_embedTypes[$this->fields['resource']];

        	$embedded[$idx]['key_p'] = $a[$item['resource']]['key_p'];
        	$embedded[$idx]['key_e'] = $a[$item['resource']]['key_e'];

        }

        $content = array();
        $containEditable = 0;

        $customFields = array();
        $cfs = $rField->loadCond( array( 'title', 'content_template', 'use_link', 'link', 'target' ), array( 'report_id'=>$this->fields[id], 'field_id'=>'', 'visible'=>'1', 'site_key'=>$site ) );
        foreach( $cfs as $num=>$cf ) {
            $customFields[$cf[title]] = $cf;
        }

        $this->_customFields = $customFields;

        foreach ( $subIds as $numS=>$subId ) {

            $contentRow = array();

            if ( is_numeric( $this->fields[resource] ) ) {

                $fields = array( 'id', 'submission_id', 'form_id', 'field_id', 'value', 'user_id' );

                $conds = array(
                    'site_key'      => $site,
                    'submission_id' => $subId
                );

                $row = $fSubm->loadCond( $fields, $conds );


            }
            else {

                $row = array();
                $dbRow = $db->getRow( 'select * from '. $this->fields[resource] ." where id='$subId'" );

                $fields = array_keys( $dbRow );
                foreach( $fields as $field ) {
                    $row[] = array( 'id'=>$subId, 'submission_id'=>$subId, 'field_id'=>$field, 'value'=>$dbRow[$field] );
                }
            }

            if ( !empty( $this->conditions ) && !$this->testConditions( $row ) )
                continue;

            foreach ( $embedded as $idx=>$item ) {

            	if ( $item[key_p] == 'submission_id' ) {
            		// this is embedded from conditional forms
			        $er = new Report();
	            	$this->_embedded[$item[name]][$numS] = $er->generate( $item[id] );
		            unset( $er );
            	}

            }

	        $tempCustom = $customFields;

            foreach( $row as $num=>$field ) {

            	// check if we need generate embedded report by this field's value

            	foreach ( $embedded as $idx=>$item ) {

	            	if ( $item[key_p] == $field['field_id'] ) {
	            		// this is embedded from conditional forms
				        $er = new Report();
				        $er->_embeddedCondition = array( 'section_id'=>$item['key_e'], 'condition'=>'=', 'case_sen'=>'0', 'value'=>$field[value] );
		            	$this->_embedded[$item[name]][$numS] = $er->generate( $item[id] );
			            unset( $er );
	            	}

	            }
                // get field label and type depending on resource

            	if ( is_numeric( $this->fields[resource] ) ) {
                    $f = $fSect->load( $field[field_id], array( 'field_type', 'label' ) );
                    preg_match( '/([a-zA-Z]+)_?([0-9]*)/', $f[field_type], $matches );
                    $fieldType = $matches[1];
                    $fieldId = $matches[2];
                    $label = $f[label];
                }
                else {
                    $fieldType = getColumnType( $field[field_id] );
                    $label = $this->_fieldReplace[$this->fields[resource]][$field[field_id]][label];
                    //$label = $field[field_id];
                }

                switch ( $fieldType ) {

                    case 'image':
                        $params = array( 'table'=>FORMSUBMISSIONS_TABLE, 'field'=>'blob_value', 'id'=>$field[id] );
                        if ( $this->settings['imageWidth'] )
                            $width = 'width='.$this->settings['imageWidth'];

                        if ( $this->settings['imageHeight'] )
                            $height = 'height='.$this->settings['imageHeight'];

                        $border = intval( $this->settings['imageBorderSize'] ).'px solid '.$this->settings['imageBorderColor'];
                        $value = "<img $width $height style='border: $border' src='".smarty_function_imgsrc($params, $t)."'>";
                        break;

                    case 'file':
                        $value = "<a href=" . DOC_ROOT . "getObject.php?mode=formFile&id=" . $field[id] . '>Download</a>';
                        break;

                    case 'date':
                        $parts = explode( '-', $field[value] ); ;
                        $value = $parts[1] . '/' . $parts[2] . '/' . $parts[0];
                        break;

                    case 'checkbox':
                        $value = ( $field[value] == 'checked' ? 'Yes' : 'No' );
                        break;

                    case 'modcat':
                        $value = $db->getOne( 'select title from ' . MODULECATEGORIES_TABLE . " where id = '$field[value]' and site_key = '$site'" );
                        break;

                    default:
                        $value = $field[value];
                        break;
                }

                $value = $this->_applyFilterOutput( $this->fields[resource], $field[field_id], $value );


                // replace headers and find visibility

                if ( !$outputFields[$label] ) {
                    $outputField = $rField->loadCond( array( 'id', 'field_id', 'display_title', 'visible', 'use_link', 'link', 'target' ), array( 'report_id'=>$this->fields[id], 'field_id'=>$field[field_id], 'site_key'=>$site ) );

                    // if there is no overriden data then display as usual
                    if ( !$outputField ) {

                        $outputField[0]['display_title'] = $label;
                        $outputField[0]['visible'] = 1;
                    }

                    // if there is no overriden data for title then display normal title
                    if ( !$outputField[0]['display_title'] )
                        $outputField[0]['display_title'] = $label;

                    $outputFields[$label] = $outputField[0];
                }

                $contentRow[$label] = $value;

                // -------------------
                // parse custom fields
                // -------------------

                foreach ( $tempCustom as $key=>$customField ) {
                    $customField['content_template'] = str_replace( '{'.$label.'}', $value, $customField['content_template'] );
                    $tempCustom[$key] = $customField;

                    if ( !$outputFields[$key] ) {
                        $outputFields[$key] = $customField;
                        $outputFields[$key]['display_title'] = $key;
                        $outputFields[$key]['visible'] = 1;
                    }
                }
            }

            foreach( $tempCustom as $key=>$customField )
                $contentRow[$key] = $customField['content_template'];

            // ---------------------------------------------
            // check if any field is linked to images or url
            // ---------------------------------------------

            $origContentRow = $contentRow;

            foreach( $origContentRow as $key=>$value ) {

                if ( $outputFields[$key]['use_link'] ) {

                    // should we open in new window ?

                	if ( $outputFields[$key]['target'] ) {
                        $target = 'target='.$outputFields[$key]['target'];
                        $newWindow = ( $outputFields[$key]['target'] == '_blank' );
                    }
                    else {
                        $newWindow = 1;
                    }

                    // parse link

                    switch( $outputFields[$key]['link'] ) {

                        case 'images': // link to all images in the record
                            $form_id = $this->fields['resource'];
                            $report_id = $this->fields[id];
                            $contentRow[$key] = "<a href='".DOC_ROOT."viewRecordImages.php?submission_id=$subId&form_id=$form_id&report_id=$report_id' border=0 $target>$value</a>";
                            break;

                        case 'full_image': // link to the full image (only for images)

                            // get the id in the submssions table
                            $r = $fSubm->loadCond( array( 'id' ), array( 'submission_id'=>$subId, 'field_id'=>$outputFields[$key]['field_id'], 'site_key'=>$site, 'form_id'=>$this->fields[resource] ) );
                            $id = $r[0]['id'];
                            $contentRow[$key] = "<a href='".DOC_ROOT."viewLargeImage.php?mode=report&id=$id&newWindow=$newWindow' border=0 $target>$value</a>";
                            break;

                        default: // link to any url that can contain other fields data

                            $link = $outputFields[$key]['link'];

                            foreach ( $contentRow as $key1=>$value1 )
                                $link = str_replace( '{'.$key1.'}', $value1, $link );

                            if ( !preg_match( '/^http(s)?:\/\//', $link ) )
                                $link = 'http://'.$link;

                            $contentRow[$key] = "<a href='$link' border=0 $target>$value</a>";
                            break;
                    }
                }
            }


            // --------------------------------
            // check if we should allow editing
            // --------------------------------

            $editable = false;

            if ( $this->settings[makeEditable] == 'yes' ) {

                $userId = $_SESSION['es_auth']['id'];
                $submitterId = $row[0]['user_id'];

                if ( $submitterId ) {

                    switch ( $this->settings[editableBy] ) {

                        case 0: // all users
                            $editable = true;
                            break;

                        case 1: // submitter only
                            $editable = $userId == $submitterId;
                            break;

                        case 2: // same group
                            $curGroup = $db->getOne( 'select group_id from '.USERS_TABLE." where id='$userId'" );
                            $allowedGroup = $db->getOne( 'select group_id from '.USERS_TABLE." where id='$submitterId'" );

                            $editable = ( $curGroup == $allowedGroup );
                            break;

                    }
                }
                else{
                    $editable = true;
                }
            }

            if ( $editable )
                $containEditable = 1;

            $content[] = array_merge( $contentRow, array( 'submission_id'=>$subId, '_editable_'=>$editable ) );

        }

        $this->content = $content;

        $this->numSubmissions = count( $content );

        $this->groupArray = array( array( 'bound'=>0 ) );

        // --------------
        // sort and group
        //---------------

        $groups = $rGroup->loadByParentId( $this->fields[id], array(), 'report' );

        $this->groupRows = $groups;

        foreach( $this->groupRows as $num=>$groupRow ) {
            $this->_sortBy( $groupRow );
        }

        // ----------
        // pagination
        // ----------

        if ( $this->settings[paginate] ) {

            include_once( INCLUDE_DIR . 'internal/class.navigation.php' );

        	$n = new Navigation();

        	// total number of items
        	$n->_total = $this->numSubmissions;

        	// number of items to display per page
        	$n->_itemsPerPage = intval( $this->settings[rows_per_page] ) ? $this->settings[rows_per_page] : 10;

        	// the number of links to show in the navigation bar (in case > 10)
        	$n->_pagesPerScreen = intval( $this->settings[page_links] ) ? $this->settings[page_links] : 10;

        	// the search vars that we should pass from screen to screen
        	$n->_requestVars = $_REQUEST;

        	$n->_separator = ' | ';

        	// the current 'start' index
        	$n->_start = intval( $_REQUEST['start'] );

        	// the current set that we are in
        	$n->_set = $_REQUEST['set'];

        	if ( !$n->_start )
        		$n->_start = '0';

            $t->assign( 'navigation', $n->output() );
            $this->nav = $n;

            //$this->content = array_slice( $this->content, $n->_start, $n->_itemsPerPage );

        }

        $this->_insertGroupLabels();

        $fieldTitles = $this->getFieldTitles( $this->fields[resource], 1 );

        $t->assign( 'report', $this->fields );
        $t->assign( 'groupsCount', count( $this->groups ) );
        $t->assign( 'containEditable', $containEditable );

        if ( !$this->fields[advanced_layout] ) {

            // --------------------
            // display simple table
            // --------------------

            $t->assign( 'headers', $fieldTitles );
            $t->assign( 'headersCount', count( $fieldTitles ) );
            $t->assign( 'outputFields', $outputFields );
            $t->assign( 'headersCount', count( $fieldTitles ) + $containEditable );
            $t->assign( 'data', $this->content );
            $t->assign( 'formName', $formName );
            $t->assign( 'numSubmissions', $this->numSubmissions );

            $output = $t->fetch( 'classes/commonReport.tpl' );


        }
        else {

            // -----------------------
            // display customized rows
            // -----------------------

            foreach ( $this->content as $numRow=>$row ) {

                if ( $row[isGroupTitle] ) {
                    $output[] = $row;
                    continue;
                }

                $r = $this->fields[layout_template];

                for ( $i=0, $n=count( $fieldTitles ); $i<$n; $i++ ) {

                	$label = $fieldTitles[$i];

        			// -----------------------
                	// insert embedded reports
                	// -----------------------

                	if ( is_array( $this->_embedded ) && count( $this->_embedded ) ) {
                		$names = array_keys( $this->_embedded );
                		foreach ( $names as $idx=>$item ) {
	                		$r = str_replace( '{embedded - ['.$item.']}', $this->_embedded[$item][$numRow], $r );
	                	}
                	}

                    $r = str_replace( '{'.$label.'}', $row[$label], $r );
                }

                $output[] = array( 'isGroupTitle'=>0, 'content'=>$r, '_editable_'=>$row['_editable_'], 'submission_id'=>$row['submission_id'] );

            }

            $t->assign( 'output', $output );
            $output = $t->fetch( 'classes/customReport.tpl' );
        }


        return $output;
    }


    function _sortBy( $group ) {

        $fSect  = new Form_Section();

        if ( $group['field_id'] ) {

            $order = $group['sort_type'] % 2 ? 'DESC' : 'ASC';
            $mode = $group['sort_type'] > 1 ? 'num' : 'str';

            if ( is_numeric( $this->fields[resource] ) ) {
                $fSect->load( $group['field_id'], array( 'label' ) );
                $sortField = $fSect->fields[label];
                $fSect->load( $group['sum_field_id'], array( 'label' ) );
                $sumField = $fSect->fields[label];
            }
            else {
                $sortField = $this->_fieldReplace[$this->fields[resource]][$group['field_id']][label];
                $sumField = $this->_fieldReplace[$this->fields[resource]][$group['sum_field_id']][label];
            }

            // sort the content in the particular group
            $groupStart = $this->groupArray[0][bound];
            $numGroups = count( $this->groupArray );

            for( $i=1; $i<$numGroups; $i++ ) {

                $groupEnd = $this->groupArray[$i][bound];

                $this->content = $this->qsort_multiarray( $this->content, $sortField, $order, $mode, $groupStart, $groupEnd-1 );

                $groupStart = $groupEnd;
            }

            // sort the last group (main if it is the only )
            $this->content = $this->qsort_multiarray( $this->content, $sortField, $order, $mode, $groupStart );

            $this->_groupBy( $sortField, $fieldNum, $sumField );

        }

        unset( $fSect );

    }


    function _groupBy( $key, $num, $sumKey ) {

        $groupArray = array( array( 'bound'=>0 ) );
        $groupNum = 0;
        $bound = 0;
        $value = $this->content[$bound][$key];
        $sum = $average = $this->content[$bound][$sumKey];
        $valueGroup = 0;
        $groupIndex = 1;

        for ( $i=1; $i<$this->numSubmissions; $i++ ) {

            if ( $this->content[$i][$key] != $value || ( $i >= $this->groupArray[$groupIndex][bound] && $groupIndex < count( $this->groupArray ) ) ) {

                // define the new group
                $groupArray[$groupNum][sum] = $sum;
                $groupArray[$groupNum][average] = $sum/($i-$bound);
                $groupArray[$groupNum][count] = $i-$bound;
                $value = $this->content[$i][$key];
                $groupArray[++$groupNum] = array( 'bound'=>$i, 'sum'=>$value, 'average'=>$value, 'count'=>1 );
                $bound = $i;
                $sum = $average = $this->content[$bound][$sumKey];
            }
            else {

                // we are in the existing group
                // add sum for this group

                $sum += $this->content[$i][$sumKey];
            }

            if ( $i >= $this->groupArray[$groupIndex][bound] && $groupIndex < count( $this->groupArray ) )
               $groupIndex++;

        }

        $groupArray[$groupNum][sum] = $sum;
        $groupArray[$groupNum][average] = $sum/($i-$bound);
        $groupArray[$groupNum][count] = $i-$bound;

        $this->groupArray = $groupArray;
        $this->groups[] = $groupArray;

    }



    function _insertGroupLabels() {

        $fSect  = new Form_Section();
        $titles = array();

        foreach( $this->groupRows as $num=>$groupRow) {

            if ( $groupRow['do_group'] && $groupRow['field_id'] ) {

                if ( is_numeric( $this->fields[resource] ) ) {
                    $fSect->load( $groupRow['field_id'], array( 'label' ) );
                    $key = $fSect->fields[label];
                }
                else {
                    $key = $this->_fieldReplace[$this->fields[resource]][$groupRow['field_id']][label];
                }

                $titles[] = $key;

            }

        }

        // may be can be done faster


        $contentWithGroup = array();

        if ( $this->settings[paginate] ) {
            $start = $this->nav->_start;
            $end = $this->nav->_start + $this->nav->_itemsPerPage;
            if ( $end > $this->numSubmissions )
                $end = $this->numSubmissions;
        }
        else {
            $start = 0;
            $end = $this->numSubmissions;
        }

        $prevGroupTitle = array();
        for ( $i=$start; $i<$end; $i++ ) {

            $j=0;
            $foundGroup = false;

            foreach( $this->groups as $n=>$group ) {

                $in_group = false;

                foreach ( $group as $n=>$g ) {
                    if ( $in_group = $i == $g[bound] ) {
                        $sum = $g[sum];
                        $average = $g[average];
                        $count = $g[count];

                        break;
                    }

                }

                if ( $in_group ) {

                    if ( $titles[$j] ) {

                        // --------------------------------
                        // parse layout of the report group
                        // --------------------------------

                        $title = $this->groupRows[$j][layout];

                        if ( $title ) {
                            $title = str_replace( '{Grouped Field}', $this->content[$i][$titles[$j]], $title );
                            $title = str_replace( '{Sum}', $sum, $title );
                            $title = str_replace( '{Average}', $average, $title );
                            $title = str_replace( '{Count}', $count, $title );
                        }
                        else {
                            $title = $this->content[$i][$titles[$j]];
                        }

                        while( !empty( $prevGroupTitle ) && $prevGroupTitle[count( $prevGroupTitle ) -1 ][ind]==$i ) {
                            $titleRow = array_pop( $prevGroupTitle );
                            $contentWithGroup[] = $titleRow;
                        }
                        // make dummy row for indent

                        if ( !$foundGroup ) {
                            $contentWithGroup[] = array( 'isGroupTitle'=>1, 'title' => '&nbsp;' , 'level'=>1 );
                            $foundGroup = 1;
                        }

                        // store group title

                        $titleRow = array( 'isGroupTitle'=>1, 'title' => $title, 'level'=>$j, 'style'=>$this->groupRows[$j][style], 'indent'=>$this->groupRows[$j][indent] );

                        if ( $this->groupRows[$j][position] == 0 ) // above position
                            $contentWithGroup[] = $titleRow;
                        else {
                             array_push( $prevGroupTitle, array( 'isGroupTitle'=>1, 'title' => $title, 'level'=>$j, 'style'=>$this->groupRows[$j][style], 'indent'=>$this->groupRows[$j][indent], 'ind'=>$i+$count ) );
                        }

                    }
                }

                $j++;
            }

            $contentWithGroup[] = $this->content[$i];

        }

        $this->content = $contentWithGroup;


        return $this->content;
    }


    function testConditions( $formContents ) {

        include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Redirect.php' );

        $fRedir = new Form_Redirect();

        $match = true;

        foreach( $this->conditions as $cond ) {
            $fieldFound = false;

            foreach( $formContents as $fc ) {

                if ( $cond['section_id'] == $fc['field_id'] ) {

                    $fieldFound = true;

                    $match = $match && $fRedir->isMatch( $cond[condition], $fc['value'], $cond[value], $cond['case_sen'] );
                    //echo $fc['value'], $cond[condition], $cond[value], $match."__<br>";
                }
            }

            $match = $match && $fieldFound;
        }

        unset( $fRedir );
//echo $match;
        return $match;
    }



    function getFieldTitles( $resource, $addCustom=0 ) {

        global $site;

        $fieldTitles = array();

        include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Section.php' );
        include_once( INCLUDE_DIR . 'internal/db_items/class.Report_Field.php' );
        $fSect = new Form_Section();
        $rField = new Report_Field();

        if ( is_numeric( $resource ) ) {

            // get form fields

            $conds = array( 'form_id'=>$resource, 'field_type!='=>'page_section' );
            $fieldTitles = $fSect->loadColumnCond( 'label', $conds, '_order' );

        }
        else if ( $resource ) {
            $fieldTitles = $this->_getTableFields( $resource );
            $fieldTitles = $this->_applyFilterNames( $resource, $fieldTitles );
        }

        if ( $addCustom ) {

            if ( !count( $this->_customFields ) ) {

                // load custom fields
                $customFields = array();
                $cfs = $rField->loadCond( array( 'title' ), array( 'report_id'=>$this->fields[id], 'field_id'=>'', 'visible'=>'1', 'site_key'=>$site ) );
                foreach( $cfs as $num=>$cf ) {
                    $customFields[$cf[title]] = $cf;
                }
                $this->_customFields = $customFields;
            }
            $fieldTitles = array_merge( $fieldTitles, array_keys( $this->_customFields ) );

            // add embedded reports
            $embedded = $this->_db->getAll( 'select r.name, r.id from '. EMBEDDEDREPORTS_TABLE.' e left join '. REPORTS_TABLE." r on e.source_id=r.id where e.into_id='{$this->fields[id]}' and e.site_key='$site'" );
            if ( is_array( $embedded ) && count( $embedded ) )
            foreach ( $embedded as $idx=>$item ) {
	            $fieldTitles[] = 'embedded - ['.$item['name'].']';
            }
        }

        unset( $fSect );
        unset( $rField );

        return $fieldTitles;
    }


    function getFieldIds( $resource ) {

        $fieldIds = array();

        include_once( INCLUDE_DIR . 'internal/db_items/class.Form_Section.php' );
        $fSect = new Form_Section();

        if ( is_numeric( $resource ) ) {

            // get form fields

            $conds = array( 'form_id'=>$resource, 'field_type!='=>'page_section' );
            $fieldIds    = $fSect->loadColumnCond( 'id', $conds, '_order' );

        }
        else if ( $resource ) {

            // get table columns as fields

            $fieldIds = $this->_getTableFields( $resource );
            $fieldIds = $this->_applyFilterIds( $resource, $fieldIds );
        }

        unset( $fSect );

        return $fieldIds;
    }


    function _getTableFields( $resource ) {

        global $db;

        $fieldTitles = array();

        $tables =  $db->getAll( 'show tables' );
        $resourceFound = false;

        foreach ( $tables as $num => $table ) {
            if ( current( $table ) == $resource )
                $resourceFound = true;
        }

        if ( $resourceFound ) {

            $columns = $db->getAll( 'show columns from '. $resource );

            foreach( $columns as $column ) {
                $fieldTitles[] = $column[Field];
            }

        }

        return $fieldTitles;
    }


    function _applyFilterNames( $resource, $fields ) {

        $fieldTitles = array();

        if ( $nameChange = $this->_fieldReplace[$resource] ) {
            foreach( $fields as $field ) {
                if ( $nameChange[$field][label] )
                    $fieldTitles[] = $nameChange[$field][label];
            }
        }

        return $fieldTitles;

    }

    function _applyFilterIds( $resource, $fields ) {

        $fieldIds = array();

        if ( $nameChange = $this->_fieldReplace[$resource] ) {
            foreach( $fields as $field ) {
                if ( $nameChange[$field][label] )
                    $fieldIds[] = $field;
            }
        }

        return $fieldIds;

    }


    function _applyFilterOutput( $resource, $field, $value ) {

        global $db;

        $output = $value;

        if ( $this->_fieldReplace[$resource][$field][filterOutput] ) {

            switch ( $field ) {

            	// ----------------------------
            	// users & groups filter output
            	// ----------------------------

                case 'group_id':
                    $output = $db->getOne( 'select name from '. GROUPS_TABLE . " where id='$value'" );
                    break;

                case 'use_expiration':
                    $output = ( $value == 0 ? 'No' : 'Yes' );
                    break;

                case 'user_site_key':
                    $output = ( $value ? 'http://' . $_SERVER['SERVER_NAME'] . DOC_ROOT . 'index.php?site=' . $value : 'Not defined' );
                    break;

                case 'email':
                    $output = "<a href='mailto:$value'>$value</a>";
                    break;

                case 'url':
                    $output = "<a href='$value' target=_blank>$value</a>";
                    break;

            	// -----------------------------
            	// product gallery filter output
            	// -----------------------------

                case 'shipping_method':
                    $output = $db->getOne( 'select name from '. DB_PREFIX . "_gallery_shipping_options where id='$value'" );
                    break;

                case 'paymenth_value':
                	switch( $value ) {
                		case 'paypal':
                		default:
                			$output = 'paypal';
                			break;
                	}
                	break;

            }
        }

        return $output;
    }
}




?>
