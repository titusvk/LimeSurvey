<?php
/**
 * Send email invitations
 */
?>

<div class="side-body">
	<h3><?php eT("Send email invitations"); ?></h3>

	<div class="row">
		<div class="col-lg-12 content-right">
            <?php echo PrepareEditorScript(true, $this); ?>
            <div>
                <?php if ($thissurvey[$baselang]['active'] != 'Y'): ?>
                    <div class="jumbotron message-box message-box-error">
                        <h2 class='text-warning'><?php eT('Warning!'); ?></h2>
                        <p class="lead text-warning">
                            <?php eT("This survey is not yet activated and so your participants won't be able to fill out the survey."); ?>
                        </p>
                    </div>
                <?php endif; ?>

                <div>
                    <?php echo CHtml::form(array("admin/tokens/sa/email/surveyid/{$surveyid}"), 'post', array('id'=>'sendinvitation', 'name'=>'sendinvitation', 'class'=>'form30')); ?>
                    <ul class="nav nav-tabs">
                        <?php
                        $c = true;
                            foreach ($surveylangs as $language)
                            {
                                echo '<li role="presentation"';

                                if ($c)
                                {
                                    $c=false;
                                    echo ' class="active"';
                                }

                                echo '><a  data-toggle="tab" href="#'.$language.'">' . getLanguageNameFromCode($language, false);
                                if ($language == $baselang)
                                {
                                    echo "(" . gT("Base language") . ")";
                                }
                                echo "</a></li>";
                            }
                        ?>
                    </ul>

                    <div class="tab-content">

                        <?php
                        $c = true;
                        foreach ($surveylangs as $language)
                        {
                                $fieldsarray["{ADMINNAME}"] = $thissurvey[$baselang]['adminname'];
                                $fieldsarray["{ADMINEMAIL}"] = $thissurvey[$baselang]['adminemail'];
                                $fieldsarray["{SURVEYNAME}"] = $thissurvey[$language]['name'];
                                $fieldsarray["{SURVEYDESCRIPTION}"] = $thissurvey[$language]['description'];
                                $fieldsarray["{EXPIRY}"] = $thissurvey[$baselang]["expiry"];

                                $subject = Replacefields($thissurvey[$language]['email_invite_subj'], $fieldsarray, false);
                                $textarea = Replacefields($thissurvey[$language]['email_invite'], $fieldsarray, false);
                                if ($ishtml !== true)
                                {
                                    $textarea = str_replace(array('<x>', '</x>'), array(''), $textarea);
                                }
                            ?>
                            <div id="<?php echo $language; ?>" class="tab-pane fade in <?php if ($c){$c=false;echo ' active';}?>">

                                <ul  class="list-unstyled">
                                    <li><label for='from_<?php echo $language; ?>'><?php eT("From"); ?>:</label>
                                        <?php echo CHtml::textField("from_{$language}",$thissurvey[$baselang]['adminname']." <".$thissurvey[$baselang]['adminemail'].">",array('size'=>50)); ?>
                                    </li>
                                    <li><label for='subject_<?php echo $language; ?>'><?php eT("Subject"); ?>:</label>
                                        <?php echo CHtml::textField("subject_{$language}",$subject,array('size'=>83)); ?>
                                    </li>

                                    <li><label for='message_<?php echo $language; ?>'><?php eT("Message"); ?>:</label>
                                        <div class="htmleditor">
                                            <?php echo CHtml::textArea("message_{$language}",$textarea,array('cols'=>80,'rows'=>20)); ?>
                                            <?php echo getEditor("email-inv", "message_$language", "[" . gT("Invitation email:", "js") . "](" . $language . ")", $surveyid, '', '', "tokens"); ?>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        <?php } ?>
                    </div>
                    <?php
                    if (count($tokenids)>0)
                    { ?>
                        <p>
                            <label><?php eT("Send invitation email to token ID(s):"); ?></label>
                        <?php echo short_implode(", ", "-", (array) $tokenids); ?></p>
                    <?php } ?>
                    <ul>
                    <li>
                        <label for='bypassbademails'><?php eT("Bypass token with failing email addresses"); ?>:</label>
                        <?php echo CHtml::dropDownList('bypassbademails', 'Y',array("Y"=>gT("Yes"),"N"=>gT("No"))); ?>
                    </li>
                    <li>
                        <?php echo CHtml::label(gT("Bypass date control before sending email."),'bypassdatecontrol', array('title'=>gt("If some tokens have a 'valid from' date set which is in the future, they will not be able to access the survey before that 'valid from' date."),'unescaped')); ?>
                        <?php echo CHtml::checkbox('bypassdatecontrol', false); ?>
                    </li>
                    </ul>
                        <p>
                            <?php
                                echo CHtml::submitButton(gT("Send Invitations"), array('class'=>'btn btn-default'));
                                echo CHtml::hiddenField('ok','absolutely');
                                echo CHtml::hiddenField('subaction','invite');
                                if (!empty($tokenids))
                                    echo CHtml::hiddenField('tokenids',implode('|', (array) $tokenids));
                            ?>
                        </p>
                    </form>
                </div>
        </form>
    </div>
</div>
