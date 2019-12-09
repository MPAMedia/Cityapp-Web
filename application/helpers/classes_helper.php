<?php

/*
 * Default tags, tables, variables and messages
 */

//MESSAGES

    class Messages {
        
        const USER_ACCESS_DENIED= "Access denied";
        const USER_NO_PERMISSION= "Sorry you don't have permission to do it";
        const USER_NOT_CREATED = "User not created  !! ";
        const USER_CREATE_ACCOUNT= "You don't have an account . could you please create it !! "; 
        const USER_STORE_EXISTE = "Store already exist ";
        const USER_LOGIN_INVALID = "Login invalid ";
        const USER_LOGIN_EMPTY="Empty login";
        const USER_PASSWORD_EMPTY="Empty password";
        const USER_DISABLED_ACCOUNT="Your account is not accessible  or it has been deactivated, please contact the Admin";

        const USER_CONFIRMED_PASSWORD = "Check your confirm password";
        const USER_ACCOUNT_LOCKED ="Sorry your account is locked";
        const USER_ACCOUNT_ISNT_BUSINESS ="Sorry your account should be for business, to connect with the dashboard, please contact our support";
        const USER_ACCOUNT_ISNT_BUSINESS_2 ="Sorry your account should be for business";
        const USER_LOGIN_PASSWORD_INCORRECT = "Auth error, login or password incorrect!";
        const USER_NOT_FOUND  ="user not found";
        const USER_NAME_INVALID = "Username is invalid or already exist!";
        const NAME_INVALID = "Your name is invalid! please insert your full name";
        const USER_NAME_EXIST = "This name is already exist  ";
        const USER_NAME_EMPTY= "username is empty ";
        const USER_ADDRESS_INVALID ="";
        const USER_ADDRESS_EMPTY ="Empty Address";
        const USER_PHONE_EMPTY= "";
        const USER_NOT_LOGGED_IN ="User not logged in";
        const USER_EMAIL_NOT_FALID = "Your mail is  not valid ";
        const USER_EMAIL_EMPTY = "Your mail is empty ";
        const USER_MISS_AUTHENTIFICATION ="Miss authentication to access this page";
        const USER_LOGIN_EMAIL_EXIST= "this username or email is already exist";
        const USER_AUTORIZATION_ACCESS = "You dont have the access to delete this post";    
        const USER_LOCATION_ERROR = "Your position is not specified or it is invalid";
        const USER_NOT_SELECTED = "user not selected";
        const USERNAME_ERROR_EMPTY = "username field is empty";
        const Name_ERROR = "Name error";
        const EMAIL_ERROR = "Email error";
        const USERNAME_ERROR_NO_VALIDE = "username field is invalid";
        const CONNECTION_NO_CONNECT = "";
        const FORGOTPASSWORD = "forgot password";
        const CHATMESSAGE_EMPTY = "";
        const ADD_COMMENT_CONTENT_EMPTY = "";
        const ADD_COMMENT_CONTENT_ERROR = "";
        const COMMENT_ID_NULL = "";
        const MISSING_MAC_ADDRESS = "missing mac address";
        const INVALID_MAC_ADDRESS = "Invalid mac_address";
        const STORE_ID = "Store_id is messing";   
        const STORE_RATE  = ""; 
        const STORE_NAME_EMPTY = "Store name is empty"; 
        const STORE_NAME_INVALID = "Store name is not valid ";
        const STORE_ADDRESS_EMPTY = "Store address is empty"; 
        const STORE_ADDRESS_INVALID = ""; 
        const STORE_PHONE_EMPTY = "Phone number is empty !!";
        const STORE_PHONE_INVALID = "Phone number is invalid !!";
        const STORE_POSITION_INVALID = ""; 
        const STORE_EXIST ="store already";
        const STORE_CATEGORY_NOT_SET ="Category is not set";
        const STORE_NOT_CREATED ="Store not created  !! ";
        const STORE_LOCATION_NOT_FOUND = "location not found !!";
        const STORE_NOT_SPECIFIED = "Store not specified !!";
        const CATEGORY_DELETE = "You should delete all  stores from this category !!";
        const CATEGORY_NOT_FOUND = "Category not found !!";
        const CATEGORY_EMPTY = "Category is empty  !!";
        const CATEGORY_NAME_EMPTY = "Category name should no be empty !!";
        const EVENT_NAME_INVALID ="Invalid event name";
        const EVENT_DESCRIPTION_INVALID ="Invalid description";
        const EVENT_DESCRIPTION_EMPTY ="description is empty";
        const EVENT_PHONE_INVALID="Invalid phone number";
        const EVENT_ADRESSE_EMPTY = "Address is empty";
        const EVENT_NAME_EMPTY ="Name is empty";
        const EVENT_DATE_BEGIN_INVALID = "date begin format not valid !";
        const EVENT_DATE_END_INVALID = "date end format not valid !";
        const EVENT_WEBSITE_INVALID =  "Enter a valid URL !!";
        const EVENT_POSITION_NOT_FOUND = "your position  is not found !";
        const EVENT_NAME_EXIST= "This name is already exist !!  ";
        const EVENT_NOT_CREATED = "event  not created  !! ";
        const EVENT_NOT_FOUND = "event  not found  !! ";
        const EVENT_NOT_SPECIFIED = "Event not specified !!";
        const NOTIFICATION_MESSAGE_EMPTY = "no message to send ";
        const RESTRICT_PERMISSION_DEMO = "This operation is denied in demo mode";
        const PERMISSION_LIMITED = "You don't have permission to do it!";
        const EXCEEDED_MAX_NBR_EVENTS = "You have exceeded the maximum number of events";
        const EXCEEDED_MAX_NBR_STORES = "You have exceeded the maximum number of stores";
        const EXCEEDED_MAX_NBR_CAMPAIGNS = "You have exceeded the maximum number of campaigns";
        const EXCEEDED_MAX_NBR_OFFERS = "You have exceeded the maximum number of offers";
        const STATUS_NOT_FOUND = "Status not found !" ;
        const TYPE_NOT_VALID = "Type is not valid";
        const CURRENCY_EXIST  = "This currency is already exist";
        const CAMPAIGN_NAME_IS_EMPTY = "Campaign name is empty";
        const SOMETHING_WRONG_12 = "Something wrong code: #12";
        const SOMETHING_WRONG_13 = "Something wrong code: #13";
        const REGISTER_ID_ERRORS = "Register id errors";
        const DATE_BEGIN_NOT_VALID = "Begin date is not valid";
        const OFFER_ID_MISSING = "Offer id is missing";
        const OFFER_NAME_MISSING = "Offer name is empty";
        const STORE_ID_NOT_VALID  = "Store id is not valid";
        const USER_PASSWORD_INVALID= "Password is not valid";
        const TOKEN_NOT_VALID = "Token isn t valid";
        const PASSWORD_FORMAT = "The password must be at least 6 characters";
        const RESET_ERROR = "Reset error";
        const LOGIN_NOT_EXIST_OR_LIMIT_EXCEEDED  = "Your login not exist or you have exceeded the limit of sending";
        const ERROR_SENDER_ID = "Error (SenderId isn't valid!)";
        const LOGIN_PASSWORD_NOT_VALID = "login or password are not valid";
        const NAME_FILED_EMPTY  = "Name field is empty";
        const EMAIL_ALREADY_EXIST  = "Your email address is already existing !!";
        const _MSG_PID  = "Your purchase id isn't valid!";
        const OFFER_VALUE_EMPTY = "Offer's value (Price Or Percent) field is empty";


    }
//TAGS
class Tags {
    
    const STORE = "store";
    const SUCCESS = "success";
    const ERROR = "error";
    const ERRORS = "errors";
    const USER = "user";
    const CHAT = "chat";
    const COUNT = "count";
    const DISCUSSION = "discussion";
    const PRODUCT = "product";
    const CITY = "city";
    const COUNTRY = "country";
    const CATS = "category";
    const SUB_CATS ="sub_category";
    const FAV ="fav";
    const RESULT = "result";
    const PAGINATION = "pagination";
    const LIKE = "like";
    const BRAND = "brand";
    const COMMENT = "comment";
    const PROMO = "promo";
    const IMAGE = "image";
    
}



