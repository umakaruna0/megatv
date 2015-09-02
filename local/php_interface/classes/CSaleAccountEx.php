<?
class CSaleAccountEx
{
    public static function transaction($sum, $user_id, $comment)
    {
        CModule::IncludeModule("sale");
        if(!CSaleUserAccount::UpdateAccount($user_id, doubleval($sum), "RUB", $comment))
        {
            return false;
        }
        
        return true;
    }
    
    public static function budget($user_id)
    {
        CModule::IncludeModule("sale");
        global $USER;
        if(!$user_id)
            $user_id = $USER->GetID();
            
        $dbAccount = CSaleUserAccount::GetList(
                array(),
                array("USER_ID" => $user_id),
                false,
                false,
                array("CURRENT_BUDGET", "CURRENCY")
            );
        if ($arAccount = $dbAccount->Fetch())
        {
            return $arAccount["CURRENT_BUDGET"];
        }else{
            return false;
        }
    }
}