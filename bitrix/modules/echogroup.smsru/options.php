<?
IncludeTemplateLangFile(__FILE__);
IncludeModuleLangFile(__FILE__);
if($_POST["submit"]){
COption::SetOptionString("echogroup.smsru","EVENTS",serialize($_POST["EVENTS"]));
COption::SetOptionString("echogroup.smsru","SUBS",serialize($_POST["SUBS"]));
COption::SetOptionString("echogroup.smsru","LOGIN",$_POST["LOGIN"]);
COption::SetOptionString("echogroup.smsru","PASSWORD",$_POST["PASSWORD"]);
COption::SetOptionString("echogroup.smsru","API_KEY",$_POST["API_KEY"]);
}
$saved_e=unserialize(COption::GetOptionString("echogroup.smsru","EVENTS"));
$saved_s=unserialize(COption::GetOptionString("echogroup.smsru","SUBS"));
CModule::IncludeModule("echogroup.smsru");

?>
<form method="post">
<table>
<tr><td><?=GetMessage("Login")?></td><td><input type="text" name="LOGIN" value="<?=COption::GetOptionString("echogroup.smsru","LOGIN")?>"/></td></tr>
<tr><td><?=GetMessage("Password")?></td><td><input type="text" name="PASSWORD" value="<?=COption::GetOptionString("echogroup.smsru","PASSWORD")?>"/></td></tr>
<tr><td><?=GetMessage("Key")?></td><td><input type="text" name="API_KEY" value="<?=COption::GetOptionString("echogroup.smsru","API_KEY")?>"/></td></tr>
<tr><td><?=GetMessage("Current_balance")?></td><td><?=(CEchogroupSmsru::CheckBalance())?></td></tr>
<tr><td><?=GetMessage("EVENT_MESS")?></td><td>
<select multiple="multiple" name="EVENTS[]" size="10"><?
$rsET=CEventMessage::GetList();
while ($arET = $rsET->Fetch())
{
    echo "<option value='".$arET["ID"]."'".(in_array($arET["ID"],$saved_e)?" selected":"").">[".$arET["EVENT_NAME"]."] ".$arET["SUBJECT"]."</option>";
}
?></select>
</td></tr>

<tr><td colspan="2">
<?=GetMessage("HELP_MESS_1")?>
<blockquote><b>
CModule::IncludeModule("echogroup.smsru");<br />
AddEventHandler("main", "OnBeforeEventSend",array("CEchogroupSmsru","TerminateEvent"));<br />
<!--AddEventHandler("subscribe", "BeforePostingSendMail", array("CEchogroupSmsru","TerminateSubscribe"));<br />-->
</b>
</blockquote>
<?=GetMessage("HELP_MESS_2")?> /bitrix/modules/echogroup.smsru/include.php<br /><br />
</td></tr>
</table>
<input type="submit" value="<?=GetMessage("Save")?>" name="submit" />
</form>

<h1><?=GetMessage("Subscribe")?></h1>
<form method="post">
<?
if(strlen($_REQUEST["subscribe"])>0){
	$address1=explode("\n",$_REQUEST["phones"]);
	$address2=$_REQUEST["users"];
	$address=array_merge((array)$address1,(array)$address2);
	foreach($address as $k=>$v) if(!preg_match("![0-9]*!",$v))unset($address[$k]);
	else{
		$address[$k]=str_replace(" ","",str_replace("(","",str_replace(")","",str_replace("+","",$v))));
	}
	if(!empty($address)&&$_REQUEST["mess"]){
		echo CEchogroupSmsru::MultySend($address,$_REQUEST["mess"]);
	}
}
?>
<table>
<tr><td><?=GetMessage("PHONE_LIST")?></td><td><textarea name="phones"></textarea></td></tr>
<tr><td><?=GetMessage("USERS")?></td><td>
<select name="users[]" multiple="multiple"><?
$res=CUser::GetList();
while($r=$res->Fetch()){
?><option value="<?=($r["PERSONAL_PHONE"]?$r["PERSONAL_PHONE"]:$r["LOGIN"])?>"><?=$r["LOGIN"]?></option><?
}
?></select>
</td></tr>
<tr><td><?=GetMessage("Message")?></td><td><textarea name="mess"></textarea></td></tr>
</table>
<input type="submit" value="<?=GetMessage("Send")?>" name="subscribe" />
</form>
