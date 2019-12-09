<?php

class EventHelper{

    public static function get($params=array(), $whereArray=array(), $method = NULL){

        $context = &get_instance();
        $result = $context->mStoreModel->getStores($params,$whereArray,$method);
        return $result;
    }

}