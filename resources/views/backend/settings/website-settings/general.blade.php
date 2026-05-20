@extends('backend.master')

@section('title', 'ตั้งค่าร้าน')

@section('content')

<div class="row">
    <div class="col-4 col-sm-2">
        <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
            @can('website_settings')
            <a class="nav-link {{ @$_GET['active-tab'] == 'website-info' ? 'active' : '' }}" id="vert-tabs-1"
                data-toggle="pill" href="#tabs-1" role="tab" aria-controls="tabs-1" aria-selected="true">
                <i class="fas fa-desktop"></i>
                &nbsp;ข้อมูลร้าน
            </a>
            @endcan
            @can('contact_settings')
            <a class="nav-link {{ @$_GET['active-tab'] == 'contacts' ? 'active' : '' }}" id="vert-tabs-2"
                data-toggle="pill" href="#tabs-2" role="tab" aria-controls="tabs-2" aria-selected="false">
                <i class="fas fa-address-book"></i>
                &nbsp;ช่องทางติดต่อ
            </a>
            @endcan
            @can('socials_settings')
            <a class="nav-link {{ @$_GET['active-tab'] == 'social-links' ? 'active' : '' }}" id="vert-tabs-3"
                data-toggle="pill" href="#tabs-3" role="tab" aria-controls="tabs-3" aria-selected="false">
                <i class="fas fa-share-alt"></i>
                &nbsp;โซเชียล
            </a>
            @endcan
            @can('style_settings')
            <a class="nav-link {{ @$_GET['active-tab'] == 'style-settings' ? 'active' : '' }}" id="vert-tabs-4"
                data-toggle="pill" href="#tabs-4" role="tab" aria-controls="tabs-4" aria-selected="false">
                <i class="fas fa-swatchbook"></i>
                &nbsp;โลโก้และภาพ
            </a>
            @endcan
            @can('custom_settings')
            <a class="nav-link {{ @$_GET['active-tab'] == 'custom-css' ? 'active' : '' }}" id="vert-tabs-5"
                data-toggle="pill" href="#tabs-5" role="tab" aria-controls="tabs-5" aria-selected="false">
                <i class="fas fa-code"></i>
                &nbsp;CSS เพิ่มเติม
            </a>
            @endcan
            @can('notification_settings')
            <a class="nav-link {{ @$_GET['active-tab'] == 'notification-settings' ? 'active' : '' }}" id="vert-tabs-6"
                data-toggle="pill" href="#tabs-6" role="tab" aria-controls="tabs-6" aria-selected="false">
                <i class="fas fa-envelope"></i>
                &nbsp;การแจ้งเตือน
            </a>
            @endcan
            @can('website_status_settings')
            <a class="nav-link {{ @$_GET['active-tab'] == 'website-status' ? 'active' : '' }}" id="vert-tabs-7"
                data-toggle="pill" href="#tabs-7" role="tab" aria-controls="tabs-7" aria-selected="false">
                <i class="fas fa-power-off"></i>
                &nbsp;สถานะระบบ
            </a>
            @endcan
            @can('invoice_settings')
            <a class="nav-link {{ @$_GET['active-tab'] == 'invoice-settings' ? 'active' : '' }}" id="vert-tabs-8"
                data-toggle="pill" href="#tabs-8" role="tab" aria-controls="tabs-8" aria-selected="false">
                <i class="fas fa-file-invoice"></i>
                &nbsp;ตั้งค่าใบเสร็จ
            </a>
            @endcan
        </div>
    </div>
    <div class="col-8 col-sm-10">
        <div class="tab-content" id="vert-tabs-tabContent">
            @can('website_settings')
            <div class="tab-pane fade {{ @$_GET['active-tab'] == 'website-info' ? 'active show' : '' }}" id="tabs-1"
                role="tabpanel" aria-labelledby="vert-tabs-1">

                <form action="{{ route('backend.admin.settings.website.info.update') }}" method="post">
                    @csrf
                    <div class="col-md-12 d-flex justify-content-between">
                        <h5>
                            <i class="fas fa-desktop"></i>
                            &nbsp;&nbsp;ข้อมูลร้าน
                        </h5>
                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fas fa-reply"></i>
                            &nbsp;บันทึก
                        </button>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>ชื่อร้าน</label>
                            <input class="form-control" name="site_name" type="text"
                                value="{{ readConfig('site_name') }}" placeholder="ชื่อร้าน">
                        </div>
                        <div class="form-group">
                            <label>คำอธิบายร้าน</label>
                            <textarea class="form-control" rows="2" name="meta_description" cols="50"
                                placeholder="คำอธิบายร้านสำหรับ SEO">{{ readConfig('meta_description') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>คำค้นหา</label>
                            <textarea class="form-control" rows="2" name="meta_keywords" cols="50" placeholder="เช่น หม่าล่า, เครื่องดื่ม, POS">{{ readConfig('meta_keywords') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>URL ระบบ</label>
                            <input class="form-control" name="site_url" type="url"
                                value="{{ readConfig('site_url') }}" placeholder="URL ระบบ">
                        </div>
                    </div>
                </form>

            </div>
            @endcan
            @can('contact_settings')
            <div class="tab-pane fade {{ @$_GET['active-tab'] == 'contacts' ? 'active show' : '' }}" id="tabs-2"
                role="tabpanel" aria-labelledby="vert-tabs-2">

                <form action="{{ route('backend.admin.settings.website.contacts.update') }}" method="post">
                    @csrf
                    <div class="col-md-12 d-flex justify-content-between">
                        <h5>
                            <i class="fas fa-address-book"></i>
                            &nbsp;&nbsp;ช่องทางติดต่อ
                        </h5>
                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fas fa-reply"></i>
                            &nbsp;บันทึก
                        </button>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>ที่อยู่ร้าน</label>
                            <input placeholder="" class="form-control" name="contact_address" type="text"
                                value="{{ readConfig('contact_address') }}">
                        </div>
                        <div class="form-group">
                            <label>เบอร์โทร</label>
                            <input placeholder="เบอร์โทรร้าน" class="form-control" name="contact_phone" type="tel"
                                value="{{ readConfig('contact_phone') }}">
                        </div>
                        <div class="form-group">
                            <label>แฟกซ์</label>
                            <input placeholder="แฟกซ์" class="form-control" name="contact_fax" type="tel"
                                value="{{ readConfig('contact_fax') }}">
                        </div>
                        <div class="form-group">
                            <label>มือถือ</label>
                            <input placeholder="มือถือ" class="form-control" name="contact_mobile" type="tel"
                                value="{{ readConfig('contact_mobile') }}">
                        </div>
                        <div class="form-group">
                            <label>อีเมล</label>
                            <input placeholder="อีเมลร้าน" class="form-control" name="contact_email" type="email"
                                value="{{ readConfig('contact_email') }}">
                        </div>
                        <div class="form-group">
                            <label>เวลาทำการ</label>
                            <input placeholder="เปิดทุกวัน 11:00 - 22:00 น." class="form-control"
                                name="working_hour" type="text" value="{{ readConfig('working_hour') }}">
                        </div>
                    </div>
                </form>

            </div>
            @endcan
            @can('socials_settings')
            <div class="tab-pane fade {{ @$_GET['active-tab'] == 'social-links' ? 'active show' : '' }}"
                id="tabs-3" role="tabpanel" aria-labelledby="vert-tabs-3">
                <form action="{{ route('backend.admin.settings.website.social.link.update') }}" method="post">
                    @csrf
                    <div class="col-md-12 d-flex justify-content-between">
                        <h5>
                            <i class="fas fa-share-alt"></i>
                            &nbsp;&nbsp;โซเชียล
                        </h5>
                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fas fa-reply"></i>
                            &nbsp;บันทึก
                        </button>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>
                                <i class="fab fa-facebook"></i>
                                &nbsp; Facebook
                            </label>
                            <input placeholder="Facebook" class="form-control" name="facebook_link" type="url"
                                value="{{ readConfig('facebook_link') }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <i class="fab fa-twitter"></i>
                                &nbsp; Twitter
                            </label>
                            <input placeholder="Twitter" class="form-control" name="twitter_link" type="url"
                                value="{{ readConfig('twitter_link') }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <i class="fab fa-linkedin"></i>
                                &nbsp; Linkedin
                            </label>
                            <input placeholder="Linkedin" class="form-control" name="linkedin_link" type="url"
                                value="{{ readConfig('linkedin_link') }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <i class="fab fa-youtube"></i>
                                &nbsp; Youtube
                            </label>
                            <input placeholder="Youtube" class="form-control" name="youtube_link" type="url"
                                value="{{ readConfig('youtube_link') }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <i class="fab fa-instagram"></i>
                                &nbsp; Instagram
                            </label>
                            <input placeholder="Instagram" class="form-control" name="instagram_link" type="url"
                                value="{{ readConfig('instagram_link') }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <i class="fab fa-pinterest"></i>
                                &nbsp; Pinterest
                            </label>
                            <input placeholder="Pinterest" class="form-control" name="pinterest_link" type="url"
                                value="{{ readConfig('pinterest_link') }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <i class="fab fa-tumblr"></i>
                                &nbsp; Tumblr
                            </label>
                            <input placeholder="Tumblr" class="form-control" name="tumblr_link" type="url"
                                value="{{ readConfig('tumblr_link') }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <i class="fab fa-snapchat"></i>
                                &nbsp; Snapchat
                            </label>
                            <input placeholder="Snapchat" class="form-control" name="snapchat_link" type="url"
                                value="{{ readConfig('snapchat_link') }}">
                        </div>

                        <div class="form-group">
                            <label>
                                <i class="fab fa-whatsapp"></i>
                                &nbsp; Whatsapp
                            </label>
                            <input placeholder="Whatsapp" class="form-control" name="whatsapp_link" type="url"
                                value="{{ readConfig('whatsapp_link') }}">
                        </div>
                    </div>
                </form>
            </div>
            @endcan
            @can('style_settings')
            <div class="tab-pane fade {{ @$_GET['active-tab'] == 'style-settings' ? 'active show' : '' }}"
                id="tabs-4" role="tabpanel" aria-labelledby="vert-tabs-4">

                <form action="{{ route('backend.admin.settings.website.style.settings.update') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12 d-flex justify-content-between">
                        <h5>
                            <i class="fas fa-swatchbook"></i>
                            &nbsp;&nbsp;โลโก้และภาพ
                        </h5>
                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fas fa-reply"></i>
                            &nbsp;บันทึก
                        </button>
                    </div>

                    <div class="col-12 my-2">
                        <label>โลโก้ร้าน</label>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="col-sm-12 box p-a-xs text-center">
                                    <img src="{{ assetImage(readconfig('site_logo')) }}"
                                        class="img-fluid thumbnail-preview site-logo-placeholder">
                                </div>
                            </div>
                        </div>
                        <input class="form-control" accept="image/*" name="site_logo" type="file"
                            onchange="previewThumbnail(this)">
                        <small>
                            <i class="far fa-question-circle"></i>
                                ( 260x60 px ) - รองรับ .png, .jpg, .jpeg, .gif, .svg
                        </small>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <label for="style_fav">ไอคอนเว็บ</label>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="col-sm-12 box p-a-xs text-center">
                                        <a target="_blank" href="{{ assetImage(readconfig('favicon_icon')) }}">
                                            <img src="{{ assetImage(readconfig('favicon_icon')) }}"
                                                class="img-fluid thumbnail-preview site-logo-placeholder">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <input class="form-control" accept="image/*" name="favicon_icon" type="file"
                                onchange="previewThumbnail(this)">
                            <small>
                                <i class="far fa-question-circle"></i>
                                ( 32x32 px ) - รองรับ .png, .jpg, .jpeg, .gif, .svg
                            </small>
                        </div>
                        <div class="col-sm-6">
                            <label for="style_apple">ไอคอน Apple</label>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="col-sm-12 box p-a-xs text-center">
                                        <a target="_blank" href="{{ assetImage(readconfig('favicon_icon_apple')) }}">
                                            <img src="{{ assetImage(readconfig('favicon_icon_apple')) }}"
                                                class="img-fluid thumbnail-preview site-logo-placeholder">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <input class="form-control" accept="image/*" name="favicon_icon_apple" type="file"
                                onchange="previewThumbnail(this)">
                            <small>
                                <i class="far fa-question-circle"></i>
                                ( 180x180 px ) - รองรับ .png, .jpg, .jpeg, .gif, .svg
                            </small>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label>รับข่าวสาร</label>
                        <div class="radio bg-white rounded pt-2 pl-2 border">
                            <label class="ui-check ui-check-md">
                                <input {{ readConfig('newsletter_subscribe') == 1 ? 'checked' : '' }}
                                    name="newsletter_subscribe" type="radio" value="1">
                                <i class="dark-white"></i>
                                เปิดใช้งาน
                            </label>
                            &nbsp; &nbsp;
                            <label class="ui-check ui-check-md">
                                <input {{ readConfig('newsletter_subscribe') == 0 ? 'checked' : '' }}
                                    name="newsletter_subscribe" type="radio" value="0">
                                <i class="dark-white"></i>
                                ปิดใช้งาน
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            @endcan
            @can('custom_settings')
            <div class="tab-pane fade {{ @$_GET['active-tab'] == 'custom-css' ? 'active show' : '' }}" id="tabs-5"
                role="tabpanel" aria-labelledby="vert-tabs-5">
                <form action="{{ route('backend.admin.settings.website.custom.css.update') }}" method="post">
                    @csrf
                    <div class="col-md-12 d-flex justify-content-between">
                        <h5>
                            <i class="fas fa-code"></i>
                            &nbsp;&nbsp;CSS เพิ่มเติม
                        </h5>
                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fas fa-reply"></i>
                            &nbsp;บันทึก
                        </button>
                    </div>
                    <div class="col-md-12 mt-2">
                        <div class="form-group">
                            <textarea placeholder="" class="form-control" rows="17" name="custom_css" cols="50">{{ readConfig('custom_css') }}</textarea>
                        </div>
                    </div>
                </form>
            </div>
            @endcan
            @can('notification_settings')
            <div class="tab-pane fade {{ @$_GET['active-tab'] == 'notification-settings' ? 'active show' : '' }}"
                id="tabs-6" role="tabpanel" aria-labelledby="vert-tabs-6">
                <form action="{{ route('backend.admin.settings.website.notification.settings.update') }}"
                    method="post">
                    @csrf
                    <div class="col-md-12 d-flex justify-content-between">
                        <h5>
                            <i class="fas fa-envelope"></i>
                            &nbsp;&nbsp;การแจ้งเตือน
                        </h5>
                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fas fa-reply"></i>
                            &nbsp;บันทึก
                        </button>
                    </div>
                    <div class="p-a-md col-md-12">
                        <div class="form-group">
                            <label>อีเมลสำหรับแจ้งเตือนระบบ</label>
                            <input placeholder="กรอกอีเมล" class="form-control" name="notify_email_address"
                                type="email" value="{{ readConfig('notify_email_address') }}">
                        </div>
                        <div class="form-group">
                            <label>ส่งอีเมลเมื่อมีข้อความติดต่อใหม่ : </label>
                            <div class="radio bg-white rounded pt-2 pl-2 border">
                                <label class="ui-check ui-check-md">
                                    <input {{ readConfig('notify_messages_status') == 1 ? 'checked' : '' }}
                                        name="notify_messages_status" type="radio" value="1">
                                    <i class="dark-white"></i>
                                    ใช่
                                </label>
                                &nbsp; &nbsp;
                                <label class="ui-check ui-check-md">
                                    <input {{ readConfig('notify_messages_status') == 0 ? 'checked' : '' }}
                                        name="notify_messages_status" type="radio" value="0">
                                    <i class="dark-white"></i>
                                    ไม่
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>ส่งอีเมลเมื่อมีความคิดเห็นใหม่ : </label>
                            <div class="radio bg-white rounded pt-2 pl-2 border">
                                <label class="ui-check ui-check-md">
                                    <input {{ readConfig('notify_comments_status') == 1 ? 'checked' : '' }}
                                        name="notify_comments_status" type="radio" value="1">
                                    <i class="dark-white"></i>
                                    ใช่
                                </label>
                                &nbsp; &nbsp;
                                <label class="ui-check ui-check-md">
                                    <input {{ readConfig('notify_comments_status') == 0 ? 'checked' : '' }}
                                        name="notify_comments_status" type="radio" value="0">
                                    <i class="dark-white"></i>
                                    ไม่
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @endcan
            @can('website_status_settings')
            <div class="tab-pane fade {{ @$_GET['active-tab'] == 'website-status' ? 'active show' : '' }}"
                id="tabs-7" role="tabpanel" aria-labelledby="vert-tabs-7">
                <form action="{{ route('backend.admin.settings.website.status.update') }}" method="post">
                    @csrf
                    <div class="col-md-12 d-flex justify-content-between">
                        <h5>
                            <i class="fas fa-power-off"></i>
                            &nbsp;&nbsp;สถานะระบบ
                        </h5>
                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fas fa-reply"></i>
                            &nbsp;บันทึก
                        </button>
                    </div>
                    <div class="p-a-md col-md-12">
                        <div class="form-group">
                            <label>สถานะระบบ : </label>
                            <div class="radio bg-white rounded pt-2 pl-2 border">
                                <label class="ui-check ui-check-md">
                                    <input {{ readConfig('is_live') == 1 ? 'checked' : '' }} name="is_live"
                                        type="radio" value="1">
                                    <i class="dark-white"></i>
                                    เปิดใช้งาน
                                </label>
                                &nbsp; &nbsp;
                                <label class="ui-check ui-check-md">
                                    <input {{ readConfig('is_live') == 0 ? 'checked' : '' }} name="is_live"
                                        type="radio" value="0">
                                    <i class="dark-white"></i>
                                    ปิดใช้งาน
                                </label>
                            </div>
                        </div>

                        <div class="form-group {{ readConfig('is_live') == 1 ? 'd-none' : '' }}" id="close_msg_div">
                            <label>ข้อความเมื่อปิดระบบ</label>
                            <textarea placeholder="ข้อความเมื่อปิดระบบ" class="form-control" rows="4" name="close_msg" cols="50">{{ readConfig('close_msg') }}</textarea>
                        </div>
                    </div>
                </form>
            </div>
            @endcan
            @can('invoice_settings')
            <div class="tab-pane fade {{ @$_GET['active-tab'] == 'invoice-settings' ? 'active show' : '' }}"
                id="tabs-8" role="tabpanel" aria-labelledby="vert-tabs-8">
                <form action="{{ route('backend.admin.settings.website.invoice.update') }}" method="post">
                    @csrf
                    <div class="col-md-12 d-flex justify-content-between">
                        <h5>
                            <i class="fas fa-file-invoice"></i>
                            &nbsp;&nbsp;ตั้งค่าใบเสร็จ
                        </h5>
                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fas fa-reply"></i>
                            &nbsp;บันทึก
                        </button>
                    </div>
                    <div class="form-group">
                        <label>ข้อความท้ายใบเสร็จ</label>
                        <input type="text" class="form-control" placeholder="ข้อความท้ายใบเสร็จ" name="note_to_customer_invoice" value="{{ readConfig('note_to_customer_invoice') }}">
                    </div>
                    <div class="form-group d-flex align-items-center">
                        <label class="switch"><input type="hidden" name="is_show_logo_invoice" value="0">
                            <input onclick="updateCheckboxValue(this)" type="checkbox" {{ readConfig('is_show_logo_invoice') == 1 ? 'checked' : '' }} name="is_show_logo_invoice" id="is_show_logo_invoice" value="{{ readConfig('is_show_logo_invoice') == 1 ? 1 : '0' }}">
                            <span class="slider round"></span>
                        </label>
                        <label for="is_show_logo_invoice" class="mx-2">โลโก้</label>
                    </div>
                    <div class="form-group d-flex align-items-center">
                        <label class="switch"><input type="hidden" name="is_show_site_invoice" value="0">
                            <input onclick="updateCheckboxValue(this)" type="checkbox" {{ readConfig('is_show_site_invoice') == 1 ? 'checked' : '' }} name="is_show_site_invoice" id="is_show_site_invoice" value="{{ readConfig('is_show_site_invoice') == 1 ? 1 : '0' }}">
                            <span class="slider round"></span>
                        </label>
                        <label for="is_show_site_invoice" class="mx-2">ชื่อร้าน</label>
                    </div>
                    <div class="form-group d-flex align-items-center">
                        <label class="switch"><input type="hidden" name="is_show_phone_invoice" value="0">
                            <input onclick="updateCheckboxValue(this)" type="checkbox" {{ readConfig('is_show_phone_invoice') == 1 ? 'checked' : '' }} name="is_show_phone_invoice" id="is_show_phone_invoice" value="{{ readConfig('is_show_phone_invoice') == 1 ? 1 : '0' }}">
                            <span class="slider round"></span>
                        </label>

                        <label for="is_show_phone_invoice" class="mx-2">เบอร์โทร</label>
                    </div>
                    <div class="form-group d-flex align-items-center">
                        <label class="switch"><input type="hidden" name="is_show_email_invoice" value="0">
                            <input onclick="updateCheckboxValue(this)" type="checkbox" {{ readConfig('is_show_email_invoice') == 1 ? 'checked' : '' }} name="is_show_email_invoice" id="is_show_email_invoice" value="{{ readConfig('is_show_email_invoice') == 1 ? 1 : '0' }}">
                            <span class="slider round"></span>
                        </label>
                        <label for="is_show_email_invoice" class="mx-2">อีเมล</label>
                    </div>
                    <div class="form-group d-flex align-items-center">
                        <label class="switch"><input type="hidden" name="is_show_address_invoice" value="0">
                            <input onclick="updateCheckboxValue(this)" type="checkbox" {{ readConfig('is_show_address_invoice') == 1 ? 'checked' : '' }} name="is_show_address_invoice" id="is_show_address_invoice" value="{{ readConfig('is_show_address_invoice') == 1 ? 1 : '0' }}">
                            <span class="slider round"></span>
                        </label>
                        <label for="is_show_address_invoice" class="mx-2">ที่อยู่</label>
                    </div>
                    <div class="form-group d-flex align-items-center">
                        <label class="switch"><input type="hidden" name="is_show_customer_invoice" value="0">
                            <input onclick="updateCheckboxValue(this)" type="checkbox" {{ readConfig('is_show_customer_invoice') == 1 ? 'checked' : '' }} name="is_show_customer_invoice" id="is_show_customer_invoice" value="{{ readConfig('is_show_customer_invoice') == 1 ? 1 : '0' }}">
                            <span class="slider round"></span>
                        </label>
                        <label for="is_show_customer_invoice" class="mx-2">ลูกค้า</label>
                    </div>
                    <div class="form-group d-flex align-items-center">
                        <label class="switch"><input type="hidden" name="is_show_note_invoice" value="0">
                            <input onclick="updateCheckboxValue(this)" type="checkbox" {{ readConfig('is_show_note_invoice') == 1 ? 'checked' : '' }} name="is_show_note_invoice" id="is_show_note_invoice" value="{{ readConfig('is_show_note_invoice') == 1 ? 1 : '0' }}">
                            <span class="slider round"></span>
                        </label>
                        <label for="is_show_note_invoice" class="mx-2">ข้อความท้ายใบเสร็จ</label>
                    </div>
                    <div class="form-group">
                        <label class="">ความกว้างใบเสร็จ POS</label>
                        <select name="receiptMaxwidth" class="form-control col-6">
                            <option value="300px" {{ readConfig('receiptMaxwidth') == '300px' ? 'selected' : '' }}>เล็ก</option>
                            <option value="400px" {{ readConfig('receiptMaxwidth') == '400px' ? 'selected' : '' }}>กลาง</option>
                            <option value="500px" {{ readConfig('receiptMaxwidth') == '500px' ? 'selected' : '' }}>ใหญ่</option>
                        </select>
                    </div>

            </div>
            @endcan
            </form>
        </div>
    </div>
</div>
</div>
@endsection

@push('script')
<script>
    $('input[type=radio][name=is_live]').on("change", function() {
        if (this.value == '0') {
            $("#close_msg_div").removeClass('d-none');
        } else {
            $("#close_msg_div").addClass('d-none');
        }
    });

    function updateCheckboxValue(checkbox) {
        checkbox.value = checkbox.checked ? '1' : '0';
    }
</script>
@endpush
