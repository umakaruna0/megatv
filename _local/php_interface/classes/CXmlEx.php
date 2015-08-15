<?
class CXmlEx
{
    static function getAttr($object, $attribute)
    {
        if(isset($object[$attribute]))
            return (string) $object[$attribute];
    }
}