<?php
namespace app\helpers;

class Constants
{

    const _IMAGE_TYPE_LOGO = 1;
    const _IMAGE_TYPE_THUMBNAIL = 2;
    const _IMAGE_TYPE_POSTER = 3;
    const _IMAGE_TYPE_SLIDER = 4;

    const _PER_PAGE_HOME = 10;
    const _PER_PAGE_LIST = 20;
    const _PER_PAGE_LIST_RELATED = 5;
    //Category
    const _CATE_MUSIC_ID = 1;
    const _CATE_MUSIC_NAME = 'Âm nhạc';
    const _CATE_CLIPS_ID = 2;
    const _CATE_CLIPS_NAME = 'Clips';
    const _CATE_FILM_ID = 3;
    const _CATE_FILM_NAME = 'Phim';
    const _CATE_LIVE_ID = 4;
    const _CATE_LIVE_NAME = 'Truyền hình';
    const _CATE_LIVE_VTV_ID = 5;
    const _CATE_LIVE_VTC_ID = 6;
    const _CATE_LIVE_AVG_ID = 7;
    const _CATE_LIVE_SCTV_ID = 8;
    const _CATE_LIVE_OTHER_ID = 9;
    const _CATE_NEWS_ID = 0;
    const _CATE_NEWS_NAME = 'Tin tức';

    //content type
    const  _TYPE_FILM = 1;//film and clips
    const  _TYPE_LIVE = 2;//live
    const  _TYPE_MUSIC = 3;//music
    const  _TYPE_NEWS = 4;//news
    const  _TYPE_CLIP = 5;//clip
    const TYPE_SAO = 6; // sao
    const TYPE_NHIPSONGTRE = 7; //nhip song tre
    const TYPE_THETHAO = 8; // the thao
    const TYPE_GIAODUC = 9; // giao duc
    const TYPE_FASHION = 10; // thoi trang
    const TYPE_GAME = 11; // game
    //filter
    const  _TYPE_SEARCH = 12;
    const  _FILTER_FEATURE = 1;//feature
    const  _FILTER_HOT = 0;//2;//hot
    const  _FILTER_ESPECIAL = 3;//dac biet
    const  _FILTER_MOST_VIEW = 0;//4;//xem nhieu

    //type support load more add home
    const  _AJAX_LOAD_HOME = 1;//hone page
    const  _AJAX_LOAD_LIST = 2;//load list
    const  _AJAX_LOAD_RELATED = 3;//lien quan

    //channel type
    const CHANNEL_TYPE_WAP = 1;
    const CHANNEL_TYPE_SMS = 2;
    const CHANNEL_TYPE_SYSTEM= 3;
    const CHANNEL_TYPE_ADMIN= 4;
}