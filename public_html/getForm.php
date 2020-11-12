<?php

if ( !defined( 'SMARTY_DIR' ) )
	include_once( 'init.php' );

include_once( INCLUDE_DIR . 'internal/db_items/class.Form.php' );
require_once INCLUDE_DIR . 'internal/class.File.php';

$form   = new Form();
$fSect  = new Form_Section();
$fGroup = new Form_Group();
$fRedir = new Form_Redirect();
//$fCond  = new Form_Condition();
$subm   = new Submission();
$fOver = new Filter_Override();

$formID = $form_id;

if ( !$user->hasAccess( 'form', $formID ) && $formID != $siteData[login_form_id] )
	loginError( ACCESS_DENIED );

// if no form id is specified, then assume the login form (get login form id from sites table)
// $siteData[login_form_id] is set in init.php

if ( !$formID || $formID == $siteData[login_form_id] ) {

	$formID = $siteData[login_form_id];

	$t->assign( 'isLoginForm', true );
	$isLoginForm = true;

	// if $formID == 0, then no login form is specified, so use default login template (login.tpl)

	if ( !$formID )
		$useDefaultLoginTemplate = true;
}


if ( $user->isShared( 'form', $formID ) )
	$formSiteKey = $user->getSharedSiteKey( 'form', $formID );
else
	$formSiteKey = $site;

$form->load( $formID );

$form->getSettings( $formID );

if ( empty( $_POST['submission_id'] ) ) {
    $fields = array( 'max(submission_id) as next' );
    $conds = array( 'site_key' => $formSiteKey );
    $next = $subm->loadCond( $fields, $conds );
    $submission_id = $next[0]['next']+1;
} else {
    $submission_id = $_POST['submission_id'];
}

$redirect_id = $_POST['redirect_id'];

$formContents = array();
$customFields = array();

$form->getFormContents( $formID, $formContents, $customFields );

foreach( $formContents as $num=>$row ) {

    $fields = array(
        'submission_id' 	=> $submission_id,
        'form_id' 			=> $formID,
        'redirect_id' 		=> $redirect_id,
        'field_id' 			=> $row['field_id'],
        'user_id' 			=> intval($_SESSION['es_auth']['id']),
        'value' 			=> $row['value'],
        'blob_value'        => $row['blob_value'],
        'file_data_path'    => $row['file_data_path'],
        'site_key' 			=> $formSiteKey
    );

    $subm->create( $fields );
}

$isFormSubmitted = count( $formContents );

if ( $isFormSubmitted && $form->fields['is_search_form'] ) {

    // get conditions from the form submission
    // if this is search form

    $conditionsFromForm = array();

    $overrides = $fOver->loadByParentId( $formID, array(), 'form' );

    foreach ( $overrides as $override ) {

        foreach( $formContents as $fc ) {

            if ( $fc[field_id] == $override[section_id] ) {

            	if ( $override['skip_empty'] ) {

            		if ( $fc['field_type'] == 'date' )
            			$fieldEmpty = $fc['value'] == '0000-00-00';
            		else
            			$fieldEmpty = empty( $fc['value'] );

            		// skip condition with empty field
            		if ( $fieldEmpty )
            			continue;
            	}

                $conditionsFromForm[] = array( 'section_id'=>$override['report_field_id'], 'condition'=>$override['condition'], 'case_sen'=>$_POST['case_sen_'.$fc[field_id]], 'value'=>$fc[value] );
                //$conditionsFromForm[] = array( 'section_id'=>$override['report_field_id'], 'condition'=>$override['condition'], 'case_sen'=>$override['case_sen'], 'value'=>$fc[value] );

            }

        }

    }

}

if ( $isFormSubmitted ) {

    // test for possible redirects
    if ( $form->fields['redirect_type'] == 'condition' ) {
        // test if we need to do conditional redirects

            if ( $matchRedId = $fRedir->testSubmission( $formContents, $formID ) ) {

                $redirect = $fRedir->load( $matchRedId );// this may be unnecessary (have testSubmission return this?)

                $redirType  = $redirect[redirect_type];
                $redirId    = $redirect[redirect_id];

            } else {

                // if there is conditional redirect
                // but any condition was not matched
                // then redirect to the 'other_redirect'

                $redirType = $form->fields['other_redirect_type'];
                $redirId   = $form->fields['other_redirect_id'];

            }

    }
    else {
        $redirType = $form->fields['redirect_type'];
        $redirId = $form->fields['redirect_id'];
    }

    if ( $form->fields['is_search_form'] ) {
        $redirType = 'searchReport';
        $redirId = $form->fields['search_report_id'];
    }

  	$t->assign( 'action', DOC_ROOT . 'getForm.php'.$query );

  	// update submission counter
	$form->update( array( 'counter_submit'=> $form->fields[counter_submit]+1 ) );

    $t->assign( 'first_form', $_POST['first_form'] );// ignored for login form
    $t->assign( 'form_to', $_POST['form_to'] );// ignored for login form
    $t->assign( 'form_cc', $_POST['form_cc'] );// ignored for login form
    $t->assign( 'form_bcc', $_POST['form_bcc'] );// ignored for login form
    $t->assign( 'form_subject', $_POST['form_subject'] );// ignored for login form

} else {

    $t->assign( 'first_form', $formID );// ignored for login form
    $t->assign( 'form_to', $form->settings['mail_to_address'] );// ignored for login form
    $t->assign( 'form_cc', $form->settings['copy_to'] );// ignored for login form
    $t->assign( 'form_bcc', $form->settings['copy_bcc'] );// ignored for login form
    $t->assign( 'form_subject', $form->settings['mail_subject'] );// ignored for login form

}

// what to do now after processign submission ?

if ( $redirType == 'page' ) {

    // -------------------------
    // not updated code for poll
    // -------------------------

    // fields that we should skip
    $skipFields = array(
    	'form_cc',
    	'form_redirect',
    	'form_subject',
    	'form_to',
    	'poll'
    );

    if ( $poll ) {

    	// update the form submission count (as in the mail sending routine)

    	$formID = $_POST['form_id'];

		if ( $user->isShared( 'form', $formID ) )
			$formSiteKey = $user->getSharedSiteKey( 'form', $formID );
		else
			$formSiteKey = $site;

    	$db->query( 'update ' . FORMS_TABLE . " set counter_submit = counter_submit+1 where id = '$formID'" );

    	// update the poll results table by looping through the post data one more time

    	foreach( $_POST as $key => $value ) {

    		if ( $key == 'startFormFields' ) {
    			$startFormFieldsReached = true;
    			continue;
    		}

    		if ( $key == 'endFormFields' ) {
    			break;
    		}

    		if ( $startFormFieldsReached ) {

    			if ( !in_array( $key, $skipFields ) ) {

    				$key = str_replace( '_', ' ', $key );

    				$userid = $_SESSION['es_auth']['id'];
    				$userip = $_SERVER['REMOTE_ADDR'];

    				$db->query( 'insert into ' . POLLRESULTS_TABLE . " (
    					poll_id,
    					label,
    					data,
    					site_key,
    					user_id,
    					user_ip
    					) values (
    					'$poll',
    					'$key',
    					'$value',
    					'$formSiteKey',
    					'$userid',
    					'$userip'
    					)"
    					);
    			}
    		}
    	}

    	// display the redirect page or form

        $_GET['page_id'] = $_POST['form_redirect'];

    	$page_id = $_POST['form_redirect'];

        //include_once( 'index.php' );
/*        $t->display( 'popupHeader' );
        $t->display( 'thanksPoll.tpl' );
        $t->display( 'popupFooter' );
        exit;
*/
    }
}


if ( $redirType == 'page' || $redirType == 'url' || $redirType == 'report' || $redirType == 'searchReport' ) {
    // end form submissions
    // send mail and display page

    // include the PHP mailer & SMTP mailer classes

    require_once INCLUDE_DIR . 'class.phpmailer.php';
    $mail = new PHPMailer();

    if ( $form->settings['email_confirmation'] == 'yes' ) {
    	$confirmMail = new PHPMailer();
    	$confirmSubject = $form->settings['email_subject'];
    	$confirmContent = $form->settings['email_contents'];
    }

    $mail->PluginDir = INCLUDE_DIR;

    foreach( $formContents as $num=>$item ) {

        $f = $fSect->load( $item[field_id], array( 'field_type', 'id', 'label' ) );

        $value = '';

        preg_match( '/([a-zA-Z]+)_?([0-9]*)/', $f[field_type], $matches );
        $fieldType = $matches[1];
        $fieldId = $matches[2];

        switch ( $fieldType ) {

            case 'file':
            case 'image':
                $c->_table = FORMSUBMISSIONS_TABLE;
                $c->_id = $f[id];
                $c->cache( stripslashes($f[blob_value]) );
                $mail->AddAttachment( $c->path( 'full' ), $f[field_name] );

                if ( $confirmMail )
                	$confirmMail->AddAttachment( $c->path( 'full' ), $f['field_name'] );

                break;

            case 'date':
                $value = @date( "M, dS Y", $item[value]);
                break;

            case 'checkbox':
                $value = ( $item[value] == 'checked' ? 'Yes' : 'No' );
                break;

            case 'modcat':
                $moduleKey = $db->getOne( 'select module_key from ' . MODULES_TABLE ." where site_key='$site' and id='$fieldId'" );
                $add_fields = array( 'site_key', 'module_key' );
                $add_values = array( $site, $moduleKey );

                $category = new Category( $db, MODULECATEGORIES_TABLE, $add_fields, $add_values );

                $categories = $category->getCategoryArray();

                $value = $categories[$item[value]];

                break;

            default:
                $value = $item[value];
                break;
        }

        if ( $value ) {

        	if ( $form->settings['strip_html']=='yes' && stripHTMLTags( $f[label] ) )
        		$fieldLabel = stripHTMLTags( $f[label] );
        	else
        		$fieldLabel = $f['label'];

        	if ( $form->settings['strip_html']=='yes' && stripHTMLTags( $value ) )
        		$fieldValue = stripHTMLTags( $value );
        	else
        		$fieldValue = $value;

        	// replace variables in receipt mail template if needed

        	if ( $confirmMail ) {
        		$confirmSubject = str_replace( '{' . $fieldLabel . '}', $fieldValue, $confirmSubject );
        		$confirmContent = str_replace( '{' . $fieldLabel . '}', $fieldValue, $confirmContent );
        	}

        	if ( $confirmMail && $form->settings['email_field'] == $f['id'] )
        		$confirmTo = $fieldValue;

            $message .= $fieldLabel.' = ' . stripslashes($fieldValue) . "\r\n";
        }
    }

    if ( $confirmMail ) {

    	// replace additional variables in receipt mail template

    	$variables = array(
    		'{site}' => $system->settings['title'],
    		'{form_title}'=>$form->settings['title'],
    		'{form_description}'=>$form->settings['description']
    	);

    	foreach ( $variables as $search=>$replace ) {

			$confirmSubject = str_replace( $search, $replace, $confirmSubject );
			$confirmContent = str_replace( $search, $replace, $confirmContent );
    	}
    }

    if ( !$_POST['form_subject'] ) {
        $_POST['form_subject'] = 'Form Submission from Website';
    }

	// send admin mail if needed

    if ( $form->settings[send_mail] == 'yes' ) {
	    if ( MAIL_TYPE == 'smtp' ) {
	        $mail->IsSMTP();
	        $mail->Host = SMTP_HOST;
	        $mail->Port = SMTP_PORT;
	        if ( SMTP_AUTH ) {
	            $mail->SMTPAuth = 1;
	            $mail->Username = SMTP_USER;
	            $mail->Password = SMTP_PASS;
	        }
	    }
	    else if ( MAIL_TYPE == 'sendmail' ) {
	        $mail->IsSendmail();
	        $mail->Sendmail = SM_PATH;
	    }
	    else {
	        $mail->IsMail();
	    }

	    if ( MAIL_FORMAT == 'html' ) {
	        $mail->IsHTML(true);
	    }
	    else {
	        $mail->IsHTML(false);
	    }

	    $mail->From = $system->settings['admin_email'];

	    $mail->FromName = $system->settings['admin_name'];

	    $mail->Priority = 3;

	    $mail->AddAddress($_POST['form_to']);

	    if ( $_POST['form_cc'] )
	    	$mail->AddCC($_POST['form_cc']);

	    if ( $_POST['form_bcc'] ) {
	    	$bccs = explode( ',', $_POST['form_bcc'] );

		    foreach ( $bccs as $num=>$bcc )
		    	$mail->AddBCC( $bcc );
	    }

	    $mail->Subject = $_POST['form_subject'];
	    $mail->Body = $message;
	    $mail->WordWrap = 50;

	    $success = $mail->Send() || 1;
	}
	else {
		$success = 1;
	}

	// send recept mail if needed

	if ( $confirmMail && $confirmTo && $confirmContent ) {
	    if ( MAIL_TYPE == 'smtp' ) {
	        $confirmMail->IsSMTP();
	        $confirmMail->Host = SMTP_HOST;
	        $confirmMail->Port = SMTP_PORT;
	        if ( SMTP_AUTH ) {
	            $confirmMail->SMTPAuth = 1;
	            $confirmMail->Username = SMTP_USER;
	            $confirmMail->Password = SMTP_PASS;
	        }
	    }
	    else if ( MAIL_TYPE == 'sendmail' ) {
	        $confirmMail->IsSendmail();
	        $confirmMail->Sendmail = SM_PATH;
	    }
	    else {
	        $confirmMail->IsMail();
	    }

	    if ( MAIL_FORMAT == 'html' ) {
	        $confirmMail->IsHTML(true);
	    }
	    else {
	        $confirmMail->IsHTML(false);
	    }

	    $confirmMail->From = $system->settings['admin_email'];

	    $confirmMail->FromName = $system->settings['admin_name'];

	    $confirmMail->Priority = 3;

	    $confirmMail->AddAddress($confirmTo);

	    $confirmMail->Subject = $confirmSubject;
	    $confirmMail->Body = $confirmContent;
	    $confirmMail->WordWrap = 50;

	    $success = $success && ( $confirmMail->Send() || 1 );
	}
	else {
		$success = $success && 1;
	}

    // delete submission if we do not need generate reports
    $form->getSettings( $_POST[first_form] );
    if ( $form->settings[generate_report] == 'no' )
        $subm->deleteCond( array( 'submission_id'=>$submission_id, 'site_key'=>$formSiteKey ) );

    if ( $success ) {

        if ( $redirType == 'page' ) {

            $_GET['page_id'] = $page_id = $redirId;
            $_GET['form_id'] = $form_id = '';

            //include_once( FULL_PATH . 'index.php' );
            header( "Location: " . DOC_ROOT . "index.php?page_id=$page_id" );
            exit;

        }
        else if ( $redirType == 'report' || $redirType == 'searchReport' ) {

            include_once( INCLUDE_DIR . 'internal/db_items/class.Report.php' );

        	$report = new Report();

        	$reportId = intval( $redirId );
        	$report->mode = 'html';

        	$report->overrideConditions = true;
        	$report->conditions = $conditionsFromForm;

        	//set specific filter criteria based on the POST data
        	//$report->setFilter( $_POST );

        	$content = $report->generate( $reportId );

        	$t->assign( 'content', $content );

        	$t->assign( 'bodyTemplate', 'pages/reportViewer.tpl' );

            $t->display( $templateName, 'report'.$reportId );

    	    exit;

        }
        else if ( $redirType == 'url' ) {

            Header( "Location: $redirId" );
            exit;
        }


    } else {
        $t->assign( 'errorMessage', 'There was an error sending the mail. Please check your mail settings in config.php.');
        $t->assign( 'bodyTemplate', 'pages/generalError.tpl' );

    	$t->display( $templateName );

        exit;
    }
}
else if ( $redirType == 'form' ) {

    // load the form on which was redirect

    if ($redirId == $formID)
      $submission_id = 0;

    $form->load( $redirId );
    $formID = $form->fields[id];
    $form->getSettings( $formID );

}



//$inTemplate = isInTemplate( 'form', $formID );

$t->assign( 'metaKeywords', $form->settings['meta_keywords'] );
$t->assign( 'metaDescription', $form->settings['meta_desc'] );
$t->assign( 'form_title', $form->settings['title'] );
$t->assign( 'form_desc', $form->settings['description'] );


$t->assign( 'submission_id', $submission_id );
$t->assign( 'redirect_id', $matchRedId );
$t->assign( 'form_id', $formID );


// update the counter for this form

$fields = array( 'counter' => $form->fields[counter] + 1 );
$form->update( $fields );

//$conds = array( 'site_key'=>$site, 'form_id'=>$form->fields[id] );
//$tempData = $fSect->loadCond( array(), $conds, '_order' );

$shared = getSQLShares( 'form' );
$tempData = $db->getAll( 'select fs.*, fo.allow_case from '. FORMSECTIONS_TABLE.' fs left join '. FILTEROVERRIDES_TABLE." fo on fs.id=fo.section_id where (fs.site_key='$site' or fs.form_id in ($shared)) and fs.form_id='".$form->fields[id]."' order by fs._order" );
$data = array();

foreach( $tempData as $index => $row ) {

    $data[] = $fSect->prepareToOutput( $row );

}

$pageTitle = htmlentities( $form->settings['page_title'] ) ? htmlentities( $form->settings['page_title'] ) : htmlentities( $form->settings['title'] );

$t->assign( 'title', $pageTitle );

$t->assign( 'radios', $radios[7] );

// fetch all data for this page
$t->assign( 'data', $data );

$t->assign_by_ref( 'formSettings', $form->settings );


// if this form is being used in a poll, then we'll be using this as a popup form

include_once( FULL_PATH . 'init_bottom.php' );

if ( $poll  ) {
	$t->display( 'popupHeader.tpl' );
	$t->display( 'pages/getForm.tpl', 'form'.$form_id );
	$t->display( 'popupFooter.tpl' );
}
else {
	if ( $useDefaultLoginTemplate )
		$bodyTemplate = 'pages/login.tpl';
	else
		$bodyTemplate = 'pages/getForm.tpl';

	$t->assign( 'bodyTemplate', $bodyTemplate );
    $t->display( $templateName, 'form'.$form_id );
}


?>