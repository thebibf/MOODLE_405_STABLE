/* eslint-disable no-unused-vars */
/*
Copyright 2017 Ziadin Givan

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

   http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

https://github.com/givanz/Vvvebjs
*/
define('local_edwiserpagebuilder/components-profilesliders', ['local_edwiserpagebuilder/jquery', 'core/ajax'], function (jQuery, Ajax) {

    function addBlocks(blocks) {
        Vvveb.ComponentsGroup['Edwiser Profile Sliders'] = blocks;
        var SETTINGTITLES = {
            TESTIMONIAL: 'Text',
            NAME: 'Name',
            IMAGE: 'Image',
            DESIGNATION: 'Designation',
            PROFILEIMG: 'Image',
            SLIDEBGCOLOR: 'Slide bg color',
            PROFILENAMECOLOR: 'Profile name color',
            PROFILEDESGCOLOR: 'Profile designation color',
            PROFILEDESCRIPTONCOLOR: 'Profile description color',
            SHOWNAVIGATIONBUTTONS: 'Show navigation buttons',
            SHOWNAVIGATIONBULLETS: 'Show navigation bullets',
            AUTOPLAYSLIDES: 'Autoplay slides',
            PAUSESLIDESONHOVER: 'Pause slides on hover',
            SLIDEINTERVAL: 'Slide interval',
            FACULTYNAME: 'Faculty name',
            FACULTYDESIGNATION: 'Faculty designation',
            BACKGROUNDIMAGEPATTERN: 'Background image pattern',
            TITLE: 'Title',
            DESCRIPTION: 'Description',
            TITLECOLOR: 'Title color',
            DESCRIPTIONCOLOR: 'Description color',
            BGCOLOR: 'Background color',
        };

        // Profile Slider 1  --> Team Design - 9
        var profilesliderhtml1 = `<section class="section-teamdesign-9 edw_teamdesign_unqreplaceid_ edw_adv_slider" id="teamdesign9_unqreplaceid_"><div class="section-container carousel-container"><div class="slider edw-carousel"><div class="slider-inner edw-slider-inner-container"><div class="slide edw-slider-item"><div class="staff-card"><div class="img-box"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/profileslider1/images/card-images/image-1.png" alt="card-1"></div><div class="slide-footer"><p class="name testimonial-user-name">Paul Floyed</p><p class="desc edw-carousel-content-para">Computer Science - This is a extra long desc for testing</p></div></div></div><div class="slide edw-slider-item active"><div class="staff-card"><div class="img-box"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/profileslider1/images/card-images/image-2.png" alt="card-1"></div><div class="slide-footer"><p class="name testimonial-user-name">Stephanie Rychel</p><p class="desc edw-carousel-content-para">Psychology</p></div></div></div><div class="slide edw-slider-item"><div class="staff-card"><div class="img-box"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/profileslider1/images/card-images/image-3.png" alt="card-1"></div><div class="slide-footer"><p class="name testimonial-user-name">Rebecca Irby</p><p class="desc edw-carousel-content-para">Biology</p></div></div></div></div></div><div class="action-wrapper"><button class="carousel-control-prev edw-control-prev edw-slide-control" type="button"><i class="fa fa-light fa-angle-left"></i></button><button class="carousel-control-next edw-control-next edw-slide-control" type="button"><i class="fa fa-light fa-angle-right"></i></button></div></div></section>`;
        var profileslidercss1 = ` .edw_teamdesign_unqreplaceid_ {padding: 40px 24px;direction: ltr;}.edw_teamdesign_unqreplaceid_ .section-container {max-width: 1320px;margin: 0 auto;}.edw_teamdesign_unqreplaceid_ .slider {padding: 4px 4px 35px;overflow: hidden;}.edw_teamdesign_unqreplaceid_ .slider-inner {position: relative;min-height: 500px;}.edw_teamdesign_unqreplaceid_ .slide {border-radius: 6px;border: 1px solid #d9d9d9;background-color: #fff;height: calc(100% - 100px);min-height: fit-content;backface-visibility: hidden;margin: 50px 0;transform-origin: center center;width: calc(33.33% - 8px);position: absolute !important;left: -40%;top: 0;transition: left 0.5s ease-in, transform 0.5s, box-shadow 0.5s;}.edw_teamdesign_unqreplaceid_ .slide .img-box {width: 100%;height: 310px;border-top-left-radius: 6px;border-top-right-radius: 6px;overflow: hidden;}.edw_teamdesign_unqreplaceid_ .slide .img-box img {height: 100%;width: 100%;object-fit: cover;object-position: top center;}.edw_teamdesign_unqreplaceid_ .slide-footer {padding: 16px 28px;}.edw_teamdesign_unqreplaceid_ .slide .name {color: #0934ba;font-size: 18px;font-weight: 400;line-height: 30px;margin: 0;}.edw_teamdesign_unqreplaceid_ .slide .desc {color: #4c5a73;font-size: 16px;font-weight: 400;margin: 0;line-height: 20px;}.edw_teamdesign_unqreplaceid_ .slide.prev {left: 0;}.edw_teamdesign_unqreplaceid_ .slide.next {left: calc(66.66% + 8px);width: calc(33.33% - 8px);}.edw_teamdesign_unqreplaceid_ .action-wrapper {display: flex;align-items: center;justify-content: center;margin: 0 auto;gap: 32px;}.edw_teamdesign_unqreplaceid_ .carousel-control-prev, .edw_teamdesign_unqreplaceid_ .carousel-control-next {position: static;width: 41px;height: 41px;background-color: white;border: 2px solid #0934ba;font-size: 20px;color: #0934ba;border-radius: 50%;opacity: 1;transition: all 0.3s ease;margin: auto 0;display: flex;justify-content: center;align-items: center;}.edw_teamdesign_unqreplaceid_ .carousel-control-prev:hover, .edw_teamdesign_unqreplaceid_ .carousel-control-next:hover {background-color: #0934ba;color: #fff;}.edw_teamdesign_unqreplaceid_ .animatetoright {animation: toright 0.5s ease-in;}@keyframes toright {0% {left: calc(66.66% + 8px);}100% {left: 100%;}}.edw_teamdesign_unqreplaceid_ .animatetoleft {animation: toleft 0.5s ease-in;}@keyframes toleft {0% {left: 110%;}100% {left: calc(66.66% + 8px);}}@media screen and (min-width: 1024px) {.edw_teamdesign_unqreplaceid_ .slide.active {left: 33.33%;z-index: 1;transform: scale(1.2);margin-right: auto;box-shadow: 0px 8px 22px 0px rgba(0, 0, 0, 0.12);}}@media screen and (max-width: 1024px) {.edw_teamdesign_unqreplaceid_ .section-container {max-width: 820px;}.edw_teamdesign_unqreplaceid_ .slider-inner {min-height: 400px;}.edw_teamdesign_unqreplaceid_ .slide {width: calc(50% - 12px);height: calc(100%);left: -60%;margin: 0;}.edw_teamdesign_unqreplaceid_ .slide.next {width: calc(50% - 12px);}.edw_teamdesign_unqreplaceid_ .slide.active {left: calc(50% + 12px);width: calc(50% - 12px);margin-left: 0;z-index: 1;transform: scale(1);box-shadow: unset;}.edw_teamdesign_unqreplaceid_ .slide.next {left: 110%;}.edw_teamdesign_unqreplaceid_ .slide.prev {left: 0%;}.edw_teamdesign_unqreplaceid_ .animatetoright {animation: torighttab 0.5s ease-in;}@keyframes torighttab {0% {left: 110%;}100% {left: 100%;}}.edw_teamdesign_unqreplaceid_ .animatetoleft {animation: tolefttab 0.5s ease-in;}@keyframes tolefttab {0% {left: 110%;}100% {left: 110%;}}}@media screen and (max-width: 767px) {.edw_teamdesign_unqreplaceid_ .section-container {max-width: 600px;}.edw_teamdesign_unqreplaceid_ .slide {width: 100%;left: -110%;}.edw_teamdesign_unqreplaceid_ .slide.active {width: 100%;left: 0;}.edw_teamdesign_unqreplaceid_ .slide.next {left: 110%;width: 100%;}.edw_teamdesign_unqreplaceid_ .slide.prev {left: -110%;}.edw_teamdesign_unqreplaceid_ .animatetoright {animation: torightmob 0.5s ease-in;}@keyframes torightmob {0% {left: 110%;}100% {left: 110%;}}.edw_teamdesign_unqreplaceid_ .animatetoleft {animation: toleftmob 0.5s ease-in;}@keyframes toleftmob {0% {left: 110%;}100% {left: 0;}}}@media screen and (min-width: 1024px) {.edw-limitedwidth-block .edw_teamdesign_unqreplaceid_ .slider-inner {min-height: 400px;}.edw-limitedwidth-block .edw_teamdesign_unqreplaceid_ .slide {width: calc(50% - 12px);height: calc(100%);left: -60%;margin: 0;}.edw-limitedwidth-block .edw_teamdesign_unqreplaceid_ .slide.next {width: calc(50% - 12px);}.edw-limitedwidth-block .edw_teamdesign_unqreplaceid_ .slide.active {left: 0;width: calc(50% - 12px);margin-left: 0;z-index: 1;transform: scale(1);box-shadow: unset;}.edw-limitedwidth-block .edw_teamdesign_unqreplaceid_ .slide.next {left: calc(50% + 12px);}.edw-limitedwidth-block .edw_teamdesign_unqreplaceid_ .slide.prev {left: -100%;}.edw-limitedwidth-block .edw_teamdesign_unqreplaceid_ .animatetoright {animation: torighttab 0.5s ease-in;}@keyframes torighttab {0% {left: calc(50% + 12px);}100% {left: 100%;}}.edw-limitedwidth-block .edw_teamdesign_unqreplaceid_ .animatetoleft {animation: tolefttab 0.5s ease-in;}@keyframes tolefttab {0% {left: 110%;}100% {left: calc(50% + 12px);}}}.edw-rtl-block .edw_teamdesign_unqreplaceid_ .carousel-control-next, .edw-rtl-block .edw_teamdesign_unqreplaceid_ .carousel-control-prev {transform: rotate(180deg);}`;
       var profilesliderjs1 = `class TeamDesign9_unqreplaceid_{constructor(){this.teamdesign9SEL="#teamdesign9_unqreplaceid_",this.teamdesign=document.querySelector(this.teamdesign9SEL),this.slider=this.teamdesign.querySelector(".slider-inner"),this.leftBtn=this.teamdesign.querySelector(".carousel-control-prev"),this.rightBtn=this.teamdesign.querySelector(".carousel-control-next"),this.isTouchStart=!1,this.startX=0,this.distance=0,this.initializeSlide=this.initializeSlide.bind(this),this.leftClick=this.leftClick.bind(this),this.rightClick=this.rightClick.bind(this),this.setSliderHeight=this.setSliderHeight.bind(this),this.touchStart=this.touchStart.bind(this),this.touchMove=this.touchMove.bind(this),this.touchStop=this.touchStop.bind(this),this.initializeEventListeners()}initializeEventListeners(){window.addEventListener("load",()=>{this.setSliderHeight(),setTimeout(()=>this.setSliderHeight(),200)}),window.addEventListener("resize",()=>{this.setSliderHeight(),setTimeout(()=>this.setSliderHeight(),200)}),this.leftBtn.addEventListener("click",this.leftClick),this.rightBtn.addEventListener("click",this.rightClick),this.slider.addEventListener("touchstart",this.touchStart),this.slider.addEventListener("touchmove",this.touchMove),this.slider.addEventListener("touchend",this.touchStop)}setSliderHeight(){let t=document.querySelector(".edw-limitedwidth-block "+this.teamdesign9SEL),e=this.teamdesign.querySelectorAll(".staff-card"),i=0;e.forEach(t=>{i=Math.max(i,t.offsetHeight),t.classList.remove("prev"),t.classList.remove("next")}),window.innerWidth>1024&&!t?this.slider.style.height=i+100+"px":this.slider.style.height=i+"px",this.initilaization()}initilaization(t="",e=""){let i=document.querySelector(".edw-limitedwidth-block "+this.teamdesign9SEL),s=t||this.teamdesign.querySelector(".slide.active");if(window.innerWidth>1024&&!i){let l=1+100/s.clientHeight;s.style.transform="scale("+l+")"}else s.style.transform="";let h=s.nextElementSibling,r=s.previousElementSibling;h||(h=this.teamdesign.querySelector(".slider .slide:first-child")),r||(r=this.teamdesign.querySelector(".slider .slide:last-child")),"left"==e&&h&&h.classList.add("animatetoleft"),(this.slider.childElementCount>2||""==t)&&(h.classList.add("next"),r.classList.remove("next"),r.classList.add("prev")),setTimeout(()=>{h&&h.classList.remove("animatetoleft")},500)}initializeSlide(t="",e=""){let i=this.teamdesign.querySelector(".slide.prev"),s=this.teamdesign.querySelector(".slide.next"),l=this.teamdesign.querySelector(".slide.active");2==this.slider.childElementCount&&(i&&(t=i,l.classList.add("next")),s&&(t=s,l.classList.add("prev"))),i&&i.classList.remove("prev"),s&&s.classList.remove("next"),l.classList.remove("active"),l.style.transform="",t.classList.add("active"),"right"==e&&s&&s.classList.add("animatetoright"),this.initilaization(t,e),setTimeout(()=>{s&&s.classList.remove("animatetoright")},500)}leftClick(){console.log("left clicked");let t=this.teamdesign.querySelector(".slider .slide.active").previousElementSibling;t||(t=this.teamdesign.querySelector(".slider .slide:last-child")),this.initializeSlide(t,"right")}rightClick(){let t=this.teamdesign.querySelector(".slider .slide.active").nextElementSibling;t||(t=this.teamdesign.querySelector(".slider .slide:first-child")),this.initializeSlide(t,"left")}touchStart(t){this.isTouchStart=!0,this.startX=t.touches[0].clientX}touchMove(t){this.isTouchStart&&(this.distance=t.touches[0].clientX-this.startX)}touchStop(){this.isTouchStart=!1,this.distance>100?this.leftClick():this.distance<-100&&this.rightClick()}}let teamdesign9_unqreplaceid_=new TeamDesign9_unqreplaceid_;`;
        var profilesliderappendnode1 = `<div class="slide edw-slider-item"><div class="staff-card"><div class="img-box"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/profileslider1/images/card-images/image-1.png" alt="card-1"></div><div class="slide-footer"><p class="name testimonial-user-name">Paul Floyed</p><p class="desc edw-carousel-content-para">Computer Science - This is a extra long desc for testing</p></div></div></div>`;
        Vvveb.Components.extend("_base", "html/profileslider1", {
            name: "Profile slider 1",
            attributes: ['data-ebpb-profileslider1'],
            image: "icons/profileslider1.png",
            classes: ['edwiser-pb-profileslider1'],
            html: (() => {
                return `<div class="edwiser-pb-profileslider1" data-vvveb-disabled-area contenteditable="false">${profilesliderhtml1}<style>${profileslidercss1}</style><script>${profilesliderjs1}</script></div>`;
            })(),
            beforeInit: function (node) {
                properties = [];
                var i = 0;
                var slideno = 0;
                var id = generateUniqueID();
                node.innerHTML = node.innerHTML.replaceAll("_unqreplaceid_", id);
                $(node).find(".edw-slider-item").each(function (e) {
                    i = generateUniqueID();
                    slideno++;
                    var regex = /edw-carousel-item-\d+/;
                    var matchedClass = "";
                    var hasMatchingClass = $(this).filter(function () {
                        var classNames = $(this).attr('class').split(' ');
                        return classNames.some(function (className) {
                            if (regex.test(className)) {
                                matchedClass = className;
                                return true;
                            }
                            return false;
                        });
                    }).length > 0;
                    $(this).removeClass(matchedClass);
                    $(this).addClass("edw-carousel-item-" + i);
                    // applyTextColorsBeforeInit(node, '.slide .card');
                    // profileDesignIndicatorHandlder(node,i);
                    properties.push(
                        {
                            name: "",
                            key: "slidergrouptitle" + slideno,
                            inputtype: EdwheaderInput,
                            edwclasses: "edwgroupheader",
                            data: {
                                header: "Member " + slideno,
                                extraclass: "edwslideheading m-0",
                                type: "h6",
                                style: ""
                            }
                        },
                        {
                            name: "",
                            key: "deleteslideritem",
                            inputtype: EdwbuttonInput,
                            child: `.edw-carousel-item-${i}`,
                            edwclasses: "edwslidedelbtn",
                            data: { text: "", icon: "la-trash", extraclasses: "btn btn-outline-danger" },
                            onChange: function (node, value, input) {
                                $(node).remove();
                                Vvveb.Components.render("html/profileslider1");
                                return node;
                            },
                        },
                        {
                            name: SETTINGTITLES.NAME,
                            key: "slidername" + i,
                            htmlAttr: "innerHTML",
                            child: `.edw-carousel-item-${i} .testimonial-user-name`,
                            inputtype: TextInput,
                            edwclasses: "edwinputfield",
                            onChange: function (node, value, input) {
                                if (value == "") {
                                    $(node).hide();
                                } else {
                                    $(node).show().text(value);
                                }
                                return node;
                            }
                        },
                        {
                            name: SETTINGTITLES.TESTIMONIAL,
                            key: "slidertestimonial" + i,
                            htmlAttr: "innerHTML",
                            child: `.edw-carousel-item-${i} .edw-carousel-content-para`,
                            inputtype: TextInput,
                            edwclasses: "edwinputfield",
                            onChange: function (node, value, input) {
                                if (value == "") {
                                    $(node).hide();
                                } else {
                                    $(node).show().text(value);
                                }
                                return node;
                            }
                        },
                        {
                            name: SETTINGTITLES.PROFILEIMG,
                            key: "sliderprofile" + i,
                            htmlAttr: 'src',
                            inputtype: ImageInput,
                            edwclasses: "edwfilefield",
                            child: `.edw-carousel-item-${i} .edw-profile-img`,
                            // onChange: function (node, value, input) {
                            //     $(node).parent().find(this.child).css("background-image", "url(" + value + ")");
                            //     return node;
                            // }
                        }
                    );
                });
                properties = removeDeleteButton(node, properties, 3);
                hideNavigatorsOnSingleSlide(node);

                if ($(node).find('.edw-slider-inner-container').children('.edw-slider-item').length >= 3) {
                    $(node).find('.edw-slider-item').first().addClass('prev').removeClass('active').removeClass('next').removeAttr('style');
                    $(node).find('.edw-slider-item.prev').next().addClass('active').removeClass('prev').removeClass('next').css('transform' , 'scale(1.23474)');
                    $(node).find('.edw-slider-item.active').next().addClass('next').removeClass('prev').removeClass('active').removeAttr('style');
                }
                if ($(node).find('.edw-slider-inner-container').children('.edw-slider-item').length == 2) {
                    $(node).find('.edw-slider-item').first().addClass('prev').removeClass('active').removeClass('next').removeAttr('style');;
                    $(node).find('.edw-slider-item.prev').next().addClass('active').removeClass('prev').removeClass('next').css('transform' , 'scale(1.23474)');
                }
                if ($(node).find('.edw-slider-inner-container').children('.edw-slider-item').length == 1) {
                    $(node).find('.edw-slider-item').first().addClass('active').removeClass('prev').removeClass('next').css('transform' , 'scale(1.23474)');
                }

                //remove all option properties
                this.properties = this.properties.filter(function (item) {
                    return item.key.indexOf("slider") === -1;
                });
                this.properties = properties.concat(this.properties);
                slideIntervalfielddisabler(node);
                return node;
            },
            properties: [
                {
                    name: "",
                    key: "addNewSlide",
                    inputtype: EdwbuttonInput,
                    edwclasses: "edwnewslidebtn",
                    data: { text: "Add new slide", icon: "la-plus", extraclasses: "btn btn-outline-primary" },
                    onChange: function (node) {
                        //render component properties again to include the new column inputs
                        $(node).parent().find('.edw-slider-inner-container').append(profilesliderappendnode1);
                        Vvveb.Components.render("html/profileslider1");

                        return node;
                    }
                },
            ]
        });

        // Profile Slider 2  --> Team Design - 10
        var profilesliderhtml2 = `<section class="section-boardmembers sec-bm_unqreplaceid_" id="teamdesign10_unqreplaceid_"><div class="section-container carousel-container"><div class="slider edw-carousel"><div class="slider-inner edw-slider-inner-container"><div class="slide edw-slider-item active" data-value="1"><div class="member-card"><div class="box"><div class="card-header"><div class="img-box"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/profileslider2/images/card-images/image-1.png" alt="card-1"></div></div></div><div class="member-card-footer"><p class="name testimonial-user-name">Brad Gore</p><p class="desc testimonial-user-desg">Phycology</p></div></div></div><div class="slide edw-slider-item next-1" data-value="2"><div class="member-card"><div class="box"><div class="card-header"><div class="img-box"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/profileslider2/images/card-images/image-2.png" alt="card-1"></div></div></div><div class="member-card-footer"><p class="name testimonial-user-name">Paul Floyed</p><p class="desc testimonial-user-desg">Sports</p></div></div></div><div class="slide edw-slider-item next-2" data-value="3"><div class="member-card"><div class="box"><div class="card-header"><div class="img-box"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/profileslider2/images/card-images/image-3.png" alt="card-1"></div></div></div><div class="member-card-footer"><p class="name testimonial-user-name">Stephanie Rychel</p><p class="desc testimonial-user-desg">Mathematics</p></div></div></div><div class="slide edw-slider-item next-3" data-value="4"><div class="member-card"><div class="box"><div class="card-header"><div class="img-box"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/profileslider2/images/card-images/image-4.png" alt="card-1"></div></div></div><div class="member-card-footer"><p class="name testimonial-user-name">Rebecca Irby</p><p class="desc testimonial-user-desg">Biology</p></div></div></div><div class="slide edw-slider-item" data-value="5"><div class="member-card"><div class="box"><div class="card-header"><div class="img-box"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/profileslider2/images/card-images/image-1.png" alt="card-1"></div></div></div><div class="member-card-footer"><p class="name testimonial-user-name">Brad Gore</p><p class="desc testimonial-user-desg">Phycology</p></div></div></div><div class="slide edw-slider-item" data-value="6"><div class="member-card"><div class="box"><div class="card-header"><div class="img-box"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/profileslider2/images/card-images/image-2.png" alt="card-1"></div></div></div><div class="member-card-footer"><p class="name testimonial-user-name">Paul Floyed</p><p class="desc testimonial-user-desg">Sports</p></div></div></div></div></div><div class="action-wrapper"><button class="carousel-control-prev edw-control-prev edw-slide-control" type="button"><i class="fa fa-light fa-angle-left"></i></button><ol class="carousel-indicators edw-carousel-indicators"><li class="active" data-value="1"></li><li data-value="2"></li><li data-value="3"></li><li data-value="4"></li><li data-value="5"></li><li data-value="6"></li></ol><button class="carousel-control-next edw-control-next edw-slide-control" type="button"><i class="fa fa-light fa-angle-right"></i></button></div></div></section>`;
        var profileslidercss2 = ` .sec-bm_unqreplaceid_ {padding: 0px 0px 50px;direction: ltr;}.sec-bm_unqreplaceid_ .section-container {max-width: 1320px;margin: 0 auto;}.sec-bm_unqreplaceid_ .slider {overflow: hidden;padding: 40px 24px 40px;}.sec-bm_unqreplaceid_ .slider-inner {position: relative;min-height: 350px;}.sec-bm_unqreplaceid_ .member-card {transform-origin: center center;transition: transform 0.5s;}.sec-bm_unqreplaceid_ .member-card .box {content: "";display: block;padding-top: 100%;position: relative;}.sec-bm_unqreplaceid_ .member-card .card-header {position: absolute !important;top: 0;left: 0;width: 100%;height: 100%;margin: 0 auto;border-radius: 100%;overflow: hidden;padding: 20px;background-color: #fff;border: 1px solid #d5ddea;transition: filter 0.3s ease;}.sec-bm_unqreplaceid_ .member-card .img-box {border-radius: 100%;overflow: hidden;height: 100%;width: 100%;}.sec-bm_unqreplaceid_ .member-card .img-box img {height: 100%;width: 100%;object-fit: cover;object-position: top center;}.sec-bm_unqreplaceid_ .member-card .card-header:hover {filter: drop-shadow(0px 8px 22px rgba(0, 0, 0, 0.1));}.sec-bm_unqreplaceid_ .member-card-footer {padding: 16px 28px 0;text-align: center;}.sec-bm_unqreplaceid_ .member-card .name {color: #313848;font-size: 16px;font-weight: 500;line-height: normal;margin: 0;}.sec-bm_unqreplaceid_ .member-card .desc {margin: 0;line-height: 28px;font-size: 16px;}.sec-bm_unqreplaceid_ .slide {position: absolute !important;top: 0;left: -30%;width: calc(25% - 18px);transition: left 0.5s ease-in;}.sec-bm_unqreplaceid_ .slide.next-3 {left: calc(75% + 18px);}.sec-bm_unqreplaceid_ .slide.next-2 {left: calc(50% + 12px);}.sec-bm_unqreplaceid_ .slide.next-1 {left: calc(25% + 6px);}.sec-bm_unqreplaceid_ .slide.active {left: 0;}.sec-bm_unqreplaceid_ .action-wrapper {display: flex;align-items: center;justify-content: center;margin: 0 auto;gap: 30px;}.sec-bm_unqreplaceid_ .carousel-control-prev, .sec-bm_unqreplaceid_ .carousel-control-next {position: static;width: 56px;height: 56px;background-color: white;border: 1px solid #9a3cdf;color: #9a3cdf;font-size: 24px;border-radius: 50%;opacity: 1;transition: all 0.3s ease;margin: auto 0;display: flex;justify-content: center;align-items: center;}.sec-bm_unqreplaceid_ .carousel-control-prev:hover, .sec-bm_unqreplaceid_ .carousel-control-next:hover {background-color: #9a3cdf;color: #fff;}.sec-bm_unqreplaceid_ .carousel-indicators {display: none;position: static;gap: 11px;width: fit-content;margin: auto 0;}.sec-bm_unqreplaceid_ .carousel-indicators li {opacity: 1;background-color: #d5ddea !important;width: 8px;height: 8px;border-radius: 50%;margin: 0;cursor: pointer;}.sec-bm_unqreplaceid_ .carousel-indicators li button {display: none;}.sec-bm_unqreplaceid_ .carousel-indicators li.active {background-color: #9a3cdf !important;}.sec-bm_unqreplaceid_ .animatetoright {animation: toright 0.5s ease-in;}@keyframes toright {0% {left: calc(75% + 24px);}100% {left: 110%;}}.sec-bm_unqreplaceid_ .animatetoleft {animation: toleft 0.5s ease-in;}@keyframes toleft {0% {left: 110%;}100% {left: calc(75% + 24px);}}@media screen and (min-width: 1200px) {.sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(5))) .slider {padding-bottom: 0;}.sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(5))) .action-wrapper {display: none;}.sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(5))) .slider-inner {display: flex;gap: 24px;justify-content: center;}.sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(5))) .slide {position: static !important;}}@media screen and (max-width: 1200px) {.sec-bm_unqreplaceid_ .slide {left: -40%;width: calc(33% - 16px);}.sec-bm_unqreplaceid_ .slide.next-3 {left: 110%;}.sec-bm_unqreplaceid_ .slide.next-2 {left: calc(66.66% + 16px);}.sec-bm_unqreplaceid_ .slide.next-1 {left: calc(33% + 8px);}.sec-bm_unqreplaceid_ .animatetoright {animation: torightsmscreen 0.5s ease-in;}@keyframes torightsmscreen {0% {left: 110%;}100% {left: 110%;}}.sec-bm_unqreplaceid_ .animatetoleft {animation: toleftsmscreen 0.5s ease-in;}@keyframes toleftsmscreen {0% {left: 110%;}100% {left: 110%;}}}@media screen and (max-width: 1200px) and (min-width: 1024px) {.sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(4))) .slider {padding-bottom: 0;}.sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(4))) .action-wrapper {display: none;}.sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(4))) .slider-inner {display: flex;gap: 24px;justify-content: center;}.sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(4))) .slide {position: static !important;}}@media screen and (max-width: 1024px) {.sec-bm_unqreplaceid_ .section-container {max-width: 820px;}.sec-bm_unqreplaceid_ .slide {left: -60%;width: calc(50% - 12px);}.sec-bm_unqreplaceid_ .slide.next-3 {left: 110%;}.sec-bm_unqreplaceid_ .slide.next-2 {left: 110%;}.sec-bm_unqreplaceid_ .slide.next-1 {left: calc(50% + 12px);}}@media screen and (max-width: 1024px) and (min-width: 767px) {.sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(3))) .slider {padding-bottom: 0;}.sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(3))) .action-wrapper {display: none;}.sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(3))) .slider-inner {display: flex;gap: 24px;justify-content: center;}.sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(3))) .slide {position: static !important;}}@media screen and (max-width: 767px) {.sec-bm_unqreplaceid_ .section-container {max-width: 600px;}.sec-bm_unqreplaceid_ .slide {left: -110%;width: 100%;}.sec-bm_unqreplaceid_ .slide.next-3 {left: 110%;}.sec-bm_unqreplaceid_ .slide.next-2 {left: 110%;}.sec-bm_unqreplaceid_ .slide.next-1 {left: 110%;}.sec-bm_unqreplaceid_ h2 {font-size: 36px;}.sec-bm_unqreplaceid_ .carousel-indicators:has(li:nth-child(6)) {display: none;}.sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(2))) .slider {padding-bottom: 0;}.sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(2))) .action-wrapper {display: none;}.sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(2))) .slider-inner {display: flex;gap: 24px;justify-content: center;}.sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(2))) .slide {position: static !important;}}@media screen and (min-width: 1024px) {.edw-limitedwidth-block .sec-bm_unqreplaceid_ .slide {left: -60%;width: calc(50% - 12px);}.edw-limitedwidth-block .sec-bm_unqreplaceid_ .slide.next-3 {left: 110%;}.edw-limitedwidth-block .sec-bm_unqreplaceid_ .slide.next-2 {left: 110%;}.edw-limitedwidth-block .sec-bm_unqreplaceid_ .slide.next-1 {left: calc(50% + 12px);}.edw-limitedwidth-block .sec-bm_unqreplaceid_ .slide.active {left: 0;}.edw-limitedwidth-block .sec-bm_unqreplaceid_ .animatetoright {animation: torighttab 0.5s ease-in;}@keyframes torighttab {0% {left: 110%;}100% {left: 110%;}}.edw-limitedwidth-block .sec-bm_unqreplaceid_ .animatetoleft {animation: tolefttab 0.5s ease-in;}@keyframes tolefttab {0% {left: 110%;}100% {left: 110%;}}.edw-limitedwidth-block .sec-bm_unqreplaceid_ .carousel-container:has(.slide:nth-child(3)) .action-wrapper {display: flex;}.edw-limitedwidth-block .sec-bm_unqreplaceid_ .carousel-container:has(.slide:nth-child(3)) .slider-inner {display: flex;gap: 24px;justify-content: center;}.edw-limitedwidth-block .sec-bm_unqreplaceid_ .carousel-container:has(.slide:nth-child(3)) .slide {position: absolute !important;}}@media screen and (min-width: 767px) {.edw-limitedwidth-block .sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(3))) .slider {padding-bottom: 0;}.edw-limitedwidth-block .sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(3))) .action-wrapper {display: none;}.edw-limitedwidth-block .sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(3))) .slider-inner {display: flex;gap: 24px;justify-content: center;}.edw-limitedwidth-block .sec-bm_unqreplaceid_ .carousel-container:not(:has(.slide:nth-child(3))) .slide {position: static !important;}}.edw-rtl-block .sec-bm_unqreplaceid_ .carousel-control-next, .edw-rtl-block .sec-bm_unqreplaceid_ .carousel-control-prev {transform: rotate(180deg);}`;
       var profilesliderjs2 = `class TeamDesign10_unqreplaceid_{constructor(){this.teamdesign=document.querySelector("#teamdesign10_unqreplaceid_"),this.slider=this.teamdesign.querySelector(".slider-inner"),this.leftBtn=this.teamdesign.querySelector(".carousel-control-prev"),this.rightBtn=this.teamdesign.querySelector(".carousel-control-next"),this.indicators=this.teamdesign.querySelectorAll(".carousel-indicators li"),this.isTouchStart=!1,this.startX=0,this.distance=0,this.initializeSlide=this.initializeSlide.bind(this),this.leftClick=this.leftClick.bind(this),this.rightClick=this.rightClick.bind(this),this.indicatorClicked=this.indicatorClicked.bind(this),this.setSliderHeight=this.setSliderHeight.bind(this),this.touchStart=this.touchStart.bind(this),this.touchMove=this.touchMove.bind(this),this.touchStop=this.touchStop.bind(this),this.initializeEventListeners()}initializeEventListeners(){window.addEventListener("load",()=>{this.setSliderHeight(),setTimeout(()=>this.setSliderHeight(),300)}),window.addEventListener("resize",()=>{this.setSliderHeight(),setTimeout(()=>this.setSliderHeight(),300)}),this.leftBtn.addEventListener("click",this.leftClick),this.rightBtn.addEventListener("click",this.rightClick),this.indicators.forEach(t=>{t.addEventListener("click",this.indicatorClicked)}),this.slider.addEventListener("touchstart",this.touchStart),this.slider.addEventListener("touchmove",this.touchMove),this.slider.addEventListener("touchend",this.touchStop)}setSliderHeight(){let t=this.teamdesign.querySelectorAll(".member-card"),i=0;t.forEach(t=>{i=Math.max(i,t.offsetHeight),t.classList.remove("next-1","next-2","next-3","next-4")}),this.slider.style.height=i+10+"px",this.initilaization()}initilaization(t="",i=""){let e=t||this.teamdesign.querySelector(".slide.active"),s=e.nextElementSibling;s||(s=this.teamdesign.querySelector(".slider .slide:first-child"));for(let l=1;l<4;l++){s.classList.add("next-"+l);let r=s.nextElementSibling;if(r||(r=this.teamdesign.querySelector(".slider .slide:first-child")),(3==l||r==e)&&t)"left"==i&&s&&s.classList.add("animatetoleft");else{if(r==e)break;s=r}}this.setCurrentIndicatorActive(e.getAttribute("data-value")),setTimeout(()=>{s&&s.classList.remove("animatetoleft")},500)}initializeSlide(t="",i=""){let e=this.teamdesign.querySelector(".slide.next-1"),s=this.teamdesign.querySelector(".slide.next-2"),l=this.teamdesign.querySelector(".slide.next-3"),r=this.teamdesign.querySelector(".slide.active");e&&e.classList.remove("next-1"),s&&s.classList.remove("next-2"),l&&l.classList.remove("next-3"),r.classList.remove("active"),t.classList.add("active"),"right"==i&&l&&l.classList.add("animatetoright"),this.initilaization(t,i),setTimeout(()=>{l&&l.classList.remove("animatetoright")},500)}leftClick(){console.log("left clicked");let t=this.teamdesign.querySelector(".slider .slide.active").previousElementSibling;t||(t=this.teamdesign.querySelector(".slider .slide:last-child")),this.initializeSlide(t,"right")}rightClick(){let t=this.teamdesign.querySelector(".slider .slide.active").nextElementSibling;t||(t=this.teamdesign.querySelector(".slider .slide:first-child")),this.initializeSlide(t,"left")}setCurrentIndicatorActive(t){let i=this.teamdesign.querySelector(".carousel-indicators li.active"),e=this.teamdesign.querySelector('.carousel-indicators li[data-value="'+t+'"]');i.classList.remove("active"),e.classList.add("active")}indicatorClicked(t){let i=this.teamdesign.querySelector(".carousel-indicators li.active"),e=t.currentTarget.getAttribute("data-value"),s=this.teamdesign.querySelector('.slider .slide[data-value="'+e+'"]'),l="left";for(let r=0;r<this.indicators.length;r++){let a=this.indicators[r];if(a===i)break;a==t.currentTarget&&(l="right")}this.setCurrentIndicatorActive(e),this.initializeSlide(s,l)}touchStart(t){this.isTouchStart=!0,this.startX=t.touches[0].clientX}touchMove(t){this.isTouchStart&&(this.distance=t.touches[0].clientX-this.startX)}touchStop(){this.isTouchStart=!1,this.distance>100?this.leftClick():this.distance<-100&&this.rightClick()}}let teamdesign10_unqreplaceid_=new TeamDesign10_unqreplaceid_;`;
        var profilesliderappendnode2 = `<div class="slide edw-slider-item" data-value="5"><div class="member-card"><div class="box"><div class="card-header"><div class="img-box"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/profileslider2/images/card-images/image-1.png" alt="card-1"></div></div></div><div class="member-card-footer"><p class="name testimonial-user-name">Brad Gore</p><p class="desc testimonial-user-desg">Phycology</p></div></div></div>`;
        Vvveb.Components.extend("_base", "html/profileslider2", {
            name: "Profile slider 2",
            attributes: ['data-ebpb-profileslider2'],
            image: "icons/profileslider2.png",
            classes: ['edwiser-pb-profileslider2'],
            html: (() => {
                return `<div class="edwiser-pb-profileslider2" data-vvveb-disabled-area contenteditable="false">${profilesliderhtml2}<style>${profileslidercss2}</style><script>${profilesliderjs2}</script></div>`;
            })(),
            beforeInit: function (node) {
                properties = [];
                var i = 0;
                var slideno = 0;
                var id = generateUniqueID();
                node.innerHTML = node.innerHTML.replaceAll("_unqreplaceid_", id);
                $(node).find(".edw-slider-item").each(function (e) {
                    i = generateUniqueID();
                    slideno++;
                    var regex = /edw-carousel-item-\d+/;
                    var matchedClass = "";
                    var hasMatchingClass = $(this).filter(function () {
                        var classNames = $(this).attr('class').split(' ');
                        return classNames.some(function (className) {
                            if (regex.test(className)) {
                                matchedClass = className;
                                return true;
                            }
                            return false;
                        });
                    }).length > 0;
                    $(this).removeClass(matchedClass);
                    $(this).addClass("edw-carousel-item-" + i);
                    $(this).attr('data-value', slideno);
                    // profileDesignIndicatorHandlder(node,i);
                    properties.push(
                        {
                            name: "",
                            key: "slidergrouptitle" + slideno,
                            inputtype: EdwheaderInput,
                            edwclasses: "edwgroupheader",
                            data: {
                                header: "Profile " + slideno,
                                extraclass: "edwslideheading m-0",
                                type: "h6",
                                style: ""
                            }
                        },
                        {
                            name: "",
                            key: "deleteslideritem",
                            inputtype: EdwbuttonInput,
                            child: `.edw-carousel-item-${i}`,
                            edwclasses: "edwslidedelbtn",
                            data: { text: "", icon: "la-trash", extraclasses: "btn btn-outline-danger" },
                            onChange: function (node, value, input) {
                                $(node).remove();
                                Vvveb.Components.render("html/profileslider2");
                                return node;
                            },
                        },
                        {
                            name: SETTINGTITLES.NAME,
                            key: "sliderprofilename" + i,
                            htmlAttr: "innerHTML",
                            child: `.edw-carousel-item-${i} .testimonial-user-name`,
                            inputtype: TextInput,
                            edwclasses: "edwinputfield",
                            onChange: function (node, value, input) {
                                if (value == "") {
                                    $(node).hide();
                                } else {
                                    $(node).show().text(value);
                                }
                                return node;
                            }
                        },
                        {
                            name: SETTINGTITLES.DESIGNATION,
                            key: "sliderdesignation" + i,
                            htmlAttr: "innerHTML",
                            child: `.edw-carousel-item-${i} .testimonial-user-desg`,
                            inputtype: TextInput,
                            edwclasses: "edwinputfield",
                            onChange: function (node, value, input) {
                                if (value == "") {
                                    $(node).hide();
                                } else {
                                    $(node).show().text(value);
                                }
                                return node;
                            }
                        },
                        {
                            name: SETTINGTITLES.IMAGE,
                            key: "sliderimage" + i,
                            htmlAttr: 'src',
                            inputtype: ImageInput,
                            edwclasses: "edwfilefield",
                            child: `.edw-carousel-item-${i} .edw-profile-img`,
                        }
                    );
                });
                properties = removeDeleteButton(node, properties, 2);
                hideNavigatorsOnSingleSlide(node);
                if (!$(node).find('.edw-slider-inner-container').children('.edw-slider-item').hasClass('active')) {
                    $(node).find('.edw-slider-item').first().addClass('active');
                }
                if (!$(node).find('.edw-carousel-indicators').children('li').hasClass('active')) {
                    $(node).find('.edw-carousel-indicators li').first().addClass('active');
                }

                var cardCount = $(node).find('.edw-slider-inner-container').children('.edw-slider-item').length;

                $(node).find('.edw-slider-item').removeClass("active next-1 next-2 next-3");

                // Add classes based on the number of cards
                if (cardCount >= 1) {
                    $(node).find(".edw-slider-item:eq(0)").addClass("active");
                }
                if (cardCount >= 2) {
                    $(node).find(".edw-slider-item:eq(1)").addClass("next-1");
                }
                if (cardCount >= 3) {
                    $(node).find(".edw-slider-item:eq(2)").addClass("next-2");
                }
                if (cardCount >= 4) {
                    $(node).find(".edw-slider-item:eq(3)").addClass("next-3");
                }

                //remove all option properties
                this.properties = this.properties.filter(function (item) {
                    return item.key.indexOf("slider") === -1;
                });
                this.properties = properties.concat(this.properties);
                // slideIntervalfielddisabler(node);
                return node;
            },
            properties: [
                {
                    name: "",
                    key: "addNewSlide",
                    inputtype: EdwbuttonInput,
                    edwclasses: "edwnewslidebtn",
                    data: { text: "Add new slide", icon: "la-plus", extraclasses: "btn btn-outline-primary" },
                    onChange: function (node) {
                        //render component properties again to include the new column inputs
                        $(node).parent().find('.edw-slider-inner-container').append(profilesliderappendnode2);
                        Vvveb.Components.render("html/profileslider2");

                        return node;
                    }
                },
            ]
        });



        // Profile Slider 2  --> Team Design - 10
        var profilesliderhtml3 = `<section class="section-boardmembers sec-bm_unqreplaceid_" id="teamdesign11_unqreplaceid_"><div class="section-container carousel-container"><div class="slider edw-carousel"><div class="slider-inner edw-slider-inner-container"><div class="slide edw-slider-item active" data-value="1"><div class="member-card"><div class="box"><div class="card-header"><div class="img-box"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/profileslider3/images/card-images/image-1.png" alt="card-1"></div></div></div><div class="member-card-footer"><p class="name testimonial-user-name">Henry Jones</p><p class="card-desc testimonial-user-desg">Founder</p></div></div></div><div class="slide edw-slider-item" data-value="2"><div class="member-card"><div class="box"><div class="card-header"><div class="img-box"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/profileslider3/images/card-images/image-2.png" alt="card-1"></div></div></div><div class="member-card-footer"><p class="name testimonial-user-name">John Smith</p><p class="card-desc testimonial-user-desg">CEO</p></div></div></div><div class="slide edw-slider-item" data-value="3"><div class="member-card"><div class="box"><div class="card-header"><div class="img-box"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/profileslider3/images/card-images/image-3.png" alt="card-1"></div></div></div><div class="member-card-footer"><p class="name testimonial-user-name">Emily Johnson</p><p class="card-desc testimonial-user-desg">Product Manager</p></div></div></div><div class="slide edw-slider-item" data-value="4"><div class="member-card"><div class="box"><div class="card-header"><div class="img-box"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/profileslider3/images/card-images/image-4.png" alt="card-1"></div></div></div><div class="member-card-footer"><p class="name testimonial-user-name">James Davis</p><p class="card-desc testimonial-user-desg">Sales Manager</p></div></div></div></div></div><div class="action-wrapper"><ol class="carousel-indicators edw-carousel-indicators"><li class="active" data-value="1"></li><li data-value="2"></li><li data-value="3"></li><li data-value="4"></li></ol></div></div></section>`;
        var profileslidercss3 = ` .sec-bm_unqreplaceid_ {padding: 40px 0px 50px;}.sec-bm_unqreplaceid_ .section-container {max-width: 1320px;margin: 0 auto;}.sec-bm_unqreplaceid_ .slider {overflow: hidden;padding: 4px 24px 4px;}.sec-bm_unqreplaceid_ .slider-inner {position: relative;min-height: 350px;display: flex;flex-wrap: wrap;gap: 46px;justify-content: center;}.sec-bm_unqreplaceid_ .slider-inner:has(.slide:nth-child(5)) {justify-content: left;}.sec-bm_unqreplaceid_ .member-card {transform-origin: center center;transition: transform 0.5s;}.sec-bm_unqreplaceid_ .member-card .box {content: "";display: block;padding-top: 100%;position: relative;}.sec-bm_unqreplaceid_ .member-card .card-header {position: absolute !important;top: 0;left: 0;width: 100%;height: 100%;margin: 0 auto;border-radius: 100%;overflow: hidden;padding: 20px;background-color: #fff;border: 1px solid #3E86F5;transition: filter 0.3s ease;}.sec-bm_unqreplaceid_ .member-card .img-box {border-radius: 100%;overflow: hidden;height: 100%;width: 100%;}.sec-bm_unqreplaceid_ .member-card .img-box img {height: 100%;width: 100%;object-fit: cover;object-position: top center;}.sec-bm_unqreplaceid_ .member-card-footer {padding: 16px 28px 0;text-align: center;}.sec-bm_unqreplaceid_ .member-card .name {color: #444;text-align: center;font-size: 16px;font-weight: 700;line-height: 22px;margin: 0;}.sec-bm_unqreplaceid_ .member-card .card-desc {color: #555;text-align: center;font-size: 20px;font-weight: 400;line-height: normal;margin: 8px 0 0;}.sec-bm_unqreplaceid_ .slide {width: calc(25% - 35px);}.sec-bm_unqreplaceid_ .action-wrapper {display: flex;align-items: center;justify-content: center;margin: 0 auto;gap: 30px;}.sec-bm_unqreplaceid_ .carousel-indicators {display: none;position: static;gap: 12px;width: fit-content;margin: auto 0;}.sec-bm_unqreplaceid_ .carousel-indicators li {opacity: 1;background-color: #D5DDEA !important;width: 11px;height: 11px;border-radius: 50%;margin: 0;cursor: pointer;}.sec-bm_unqreplaceid_ .carousel-indicators li button {display: none;}.sec-bm_unqreplaceid_ .carousel-indicators li.active {background-color: #3E86F5 !important;}@media screen and (max-width: 1024px) {.sec-bm_unqreplaceid_ .section-container {max-width: 820px;}.sec-bm_unqreplaceid_ .slider-inner:has(.slide:nth-child(3)) {justify-content: left;}.sec-bm_unqreplaceid_ .slide {width: calc(50% - 23px);}.sec-bm_unqreplaceid_ .member-card {max-width: 273px;margin: 0 auto;}}@media screen and (max-width: 767px) {.sec-bm_unqreplaceid_ .section-container {max-width: 600px;}.sec-bm_unqreplaceid_ .carousel-indicators {display: flex;}.sec-bm_unqreplaceid_ .slider {overflow: hidden;padding: 4px 24px 40px;}.sec-bm_unqreplaceid_ .slide {position: absolute !important;top: 0;left: -110%;width: 100%;transition: left 0.5s ease-in;}.sec-bm_unqreplaceid_ .slide.active {left: 0;}@keyframes toRight {0% {left: 0%;}100% {left: 110%;}}@keyframes toLeft {0% {left: 110%;}100% {left: 0%;}}}@media screen and (min-width: 1024px) {.edw-limitedwidth-block .sec-bm_unqreplaceid_ .slider-inner:has(.slide:nth-child(3)) {justify-content: left;}.edw-limitedwidth-block .sec-bm_unqreplaceid_ .slide {width: calc(50% - 23px);}}`;
       var profilesliderjs3 = ` class TeamDesign11_unqreplaceid_{constructor(){this.teamdesign=document.querySelector("#teamdesign11_unqreplaceid_"),this.slider=this.teamdesign.querySelector(".slider-inner"),this.indicators=this.teamdesign.querySelectorAll(".carousel-indicators li"),this.isTouchStart=!1,this.startX=0,this.distance=0,this.initializeSlide=this.initializeSlide.bind(this),this.leftClick=this.leftClick.bind(this),this.rightClick=this.rightClick.bind(this),this.indicatorClicked=this.indicatorClicked.bind(this),this.setSliderHeight=this.setSliderHeight.bind(this),this.touchStart=this.touchStart.bind(this),this.touchMove=this.touchMove.bind(this),this.touchStop=this.touchStop.bind(this),this.initializeEventListeners()}initializeEventListeners(){window.addEventListener("load",(()=>{this.setSliderHeight(),setTimeout((()=>this.setSliderHeight()),300)})),window.addEventListener("resize",(()=>{this.setSliderHeight(),setTimeout((()=>this.setSliderHeight()),300)})),this.indicators.forEach((t=>{t.addEventListener("click",this.indicatorClicked)})),this.slider.addEventListener("touchstart",this.touchStart),this.slider.addEventListener("touchmove",this.touchMove),this.slider.addEventListener("touchend",this.touchStop)}setSliderHeight(){if(window.innerWidth<768){let t=this.teamdesign.querySelectorAll(".member-card"),i=0;t.forEach((t=>{i=Math.max(i,t.offsetHeight)})),this.slider.style.height=i+10+"px"}else this.slider.style.height=""}initializeSlide(t="",i=""){let e=this.teamdesign.querySelector(".slide.active");e.classList.remove("active"),t.classList.add("active"),"right"==i?e.style.animation="toRight 0.5s ease-in forwards":t.style.animation="toLeft 0.5s ease-in forwards",this.setCurrentIndicatorActive(t.getAttribute("data-value")),setTimeout((()=>{e.style.animation="",t.style.animation=""}),500)}leftClick(){let t=this.teamdesign.querySelector(".slider .slide.active").previousElementSibling;t||(t=this.teamdesign.querySelector(".slider .slide:last-child")),this.initializeSlide(t,"right")}rightClick(){let t=this.teamdesign.querySelector(".slider .slide.active").nextElementSibling;t||(t=this.teamdesign.querySelector(".slider .slide:first-child")),this.initializeSlide(t,"left")}setCurrentIndicatorActive(t){let i=this.teamdesign.querySelector(".carousel-indicators li.active"),e=this.teamdesign.querySelector('.carousel-indicators li[data-value="'+t+'"]');i.classList.remove("active"),e.classList.add("active")}indicatorClicked(t){let i=this.teamdesign.querySelector(".carousel-indicators li.active"),e=t.currentTarget.getAttribute("data-value"),s=this.teamdesign.querySelector('.slider .slide[data-value="'+e+'"]'),r="left";for(let e=0;e<this.indicators.length;e++){const s=this.indicators[e];if(s===i)break;s==t.currentTarget&&(r="right")}this.setCurrentIndicatorActive(e),this.initializeSlide(s,r)}touchStart(t){this.isTouchStart=!0,this.startX=t.touches[0].clientX}touchMove(t){this.isTouchStart&&(this.distance=t.touches[0].clientX-this.startX)}touchStop(){this.isTouchStart=!1,this.distance>100?this.leftClick():this.distance<-100&&this.rightClick()}}let teamdesign11_unqreplaceid_=new TeamDesign11_unqreplaceid_;`;
        var profilesliderappendnode3 = `<div class="slide edw-slider-item" data-value="3"><div class="member-card"><div class="box"><div class="card-header"><div class="img-box"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/profileslider3/images/card-images/image-3.png" alt="card-1"></div></div></div><div class="member-card-footer"><p class="name testimonial-user-name">Emily Johnson</p><p class="card-desc testimonial-user-desg">Product Manager</p></div></div></div>`;
        Vvveb.Components.extend("_base", "html/profileslider3", {
            name: "Profile slider 3",
            attributes: ['data-ebpb-profileslider3'],
            image: "icons/profileslider3.svg",
            classes: ['edwiser-pb-profileslider3'],
            html: (() => {
                return `<div class="edwiser-pb-profileslider3" data-vvveb-disabled-area contenteditable="false">${profilesliderhtml3}<style>${profileslidercss3}</style><script>${profilesliderjs3}</script></div>`;
            })(),
            beforeInit: function (node) {
                properties = [];
                var i = 0;
                var slideno = 0;
                var id = generateUniqueID();
                node.innerHTML = node.innerHTML.replaceAll("_unqreplaceid_", id);
                $(node).find(".edw-slider-item").each(function (e) {
                    i = generateUniqueID();
                    slideno++;
                    var regex = /edw-carousel-item-\d+/;
                    var matchedClass = "";
                    var hasMatchingClass = $(this).filter(function () {
                        var classNames = $(this).attr('class').split(' ');
                        return classNames.some(function (className) {
                            if (regex.test(className)) {
                                matchedClass = className;
                                return true;
                            }
                            return false;
                        });
                    }).length > 0;
                    $(this).removeClass(matchedClass);
                    $(this).addClass("edw-carousel-item-" + i);
                    $(this).attr('data-value', slideno);
                    profileDesignIndicatorHandlder(node,i);
                    properties.push(
                        {
                            name: "",
                            key: "slidergrouptitle" + slideno,
                            inputtype: EdwheaderInput,
                            edwclasses: "edwgroupheader",
                            data: {
                                header: "Profile " + slideno,
                                extraclass: "edwslideheading m-0",
                                type: "h6",
                                style: ""
                            }
                        },
                        {
                            name: "",
                            key: "deleteslideritem",
                            inputtype: EdwbuttonInput,
                            child: `.edw-carousel-item-${i}`,
                            edwclasses: "edwslidedelbtn",
                            data: { text: "", icon: "la-trash", extraclasses: "btn btn-outline-danger" },
                            onChange: function (node, value, input) {
                                $(node).remove();
                                Vvveb.Components.render("html/profileslider3");
                                return node;
                            },
                        },
                        {
                            name: SETTINGTITLES.NAME,
                            key: "sliderprofilename" + i,
                            htmlAttr: "innerHTML",
                            child: `.edw-carousel-item-${i} .testimonial-user-name`,
                            inputtype: TextInput,
                            edwclasses: "edwinputfield",
                            onChange: function (node, value, input) {
                                if (value == "") {
                                    $(node).hide();
                                } else {
                                    $(node).show().text(value);
                                }
                                return node;
                            }
                        },
                        {
                            name: SETTINGTITLES.DESIGNATION,
                            key: "sliderdesignation" + i,
                            htmlAttr: "innerHTML",
                            child: `.edw-carousel-item-${i} .testimonial-user-desg`,
                            inputtype: TextInput,
                            edwclasses: "edwinputfield",
                            onChange: function (node, value, input) {
                                if (value == "") {
                                    $(node).hide();
                                } else {
                                    $(node).show().text(value);
                                }
                                return node;
                            }
                        },
                        {
                            name: SETTINGTITLES.IMAGE,
                            key: "sliderimage" + i,
                            htmlAttr: 'src',
                            inputtype: ImageInput,
                            edwclasses: "edwfilefield",
                            child: `.edw-carousel-item-${i} .edw-profile-img`,
                        }
                    );
                });
                properties = removeDeleteButton(node, properties, 2);
                // hideNavigatorsOnSingleSlide(node);
                if (!$(node).find('.edw-slider-inner-container').children('.edw-slider-item').hasClass('active')) {
                    $(node).find('.edw-slider-item').first().addClass('active');
                }

                if (!$(node).find('.edw-carousel-indicators').children('li').hasClass('active')) {
                    $(node).find('.edw-carousel-indicators li').first().addClass('active');
                }
                //remove all option properties
                this.properties = this.properties.filter(function (item) {
                    return item.key.indexOf("slider") === -1;
                });
                this.properties = properties.concat(this.properties);
                // slideIntervalfielddisabler(node);
                return node;
            },
            properties: [
                {
                    name: "",
                    key: "addNewSlide",
                    inputtype: EdwbuttonInput,
                    edwclasses: "edwnewslidebtn",
                    data: { text: "Add new slide", icon: "la-plus", extraclasses: "btn btn-outline-primary" },
                    onChange: function (node) {
                        //render component properties again to include the new column inputs
                        $(node).parent().find('.edw-slider-inner-container').append(profilesliderappendnode3);
                        Vvveb.Components.render("html/profileslider3");

                        return node;
                    }
                },
            ]
        });

        // Profile Slider 4  --> Team Design - 9
        var sliderhtml16 = `<section class="medical-block-3 section-testimonial-design_unqreplaceid_ edw_adv_slider"><div id="edw_testimonial_unqreplaceid_" class="carousel carousel-dark slide edw-carousel" data-ride="carousel" data-interval="3000" data-pause="hover" data-touch="true" data-bs-ride="carousel" data-bs-interval="3000" data-bs-pause="hover" data-bs-touch="true"><div class="carousel-inner edw-slider-inner-container" id="carousel_container"><div class="carousel-item edw-slider-item active" data-value="0"><div class="card item-container"><div class="card-left"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/teamdesign9/images/slider-image/image-1.png" alt="image-1"></div><div class="card-right"><div class="edw-carousel-content"><h1 class="edw-carousel-content-heading">Meet Our expert faculty</h1><p class="edw-carousel-content-para">Our faculty includes seasoned doctors, specialists, and educators committed to your success.</p></div><div class="profile-navigator"><div class="profile"><p class="testimonial-user-name">Shina William</p><p class="testimonial-user-desg">MBBS, MD – Senior Medical Trainer</p></div></div></div><img class="bg-big-circle" src="${Vvveb.serverurl}/CDN/teamdesign9/images/big-circle.png" alt=""><img class="bg-small-circle" src="${Vvveb.serverurl}/CDN/teamdesign9/images/small-circle.png" alt=""><img class="bg-left-pattern" src="${Vvveb.serverurl}/CDN/teamdesign9/images/left-pattern.png" alt=""><img class="bg-right-pattern" src="${Vvveb.serverurl}/CDN/teamdesign9/images/right-pattern.png" alt=""></div></div><div class="carousel-item edw-slider-item" data-value="1"><div class="card item-container"><div class="card-left"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/teamdesign9/images/slider-image/image-1.png" alt="image-1"></div><div class="card-right"><div class="edw-carousel-content"><h1 class="edw-carousel-content-heading">Meet Our expert faculty</h1><p class="edw-carousel-content-para">Our faculty includes seasoned doctors, specialists, and educators committed to your success.</p></div><div class="profile-navigator"><div class="profile"><p class="testimonial-user-name">Shina William</p><p class="testimonial-user-desg">MBBS, MD – Senior Medical Trainer</p></div></div></div><img class="bg-big-circle" src="${Vvveb.serverurl}/CDN/teamdesign9/images/big-circle.png" alt=""><img class="bg-small-circle" src="${Vvveb.serverurl}/CDN/teamdesign9/images/small-circle.png" alt=""><img class="bg-left-pattern" src="${Vvveb.serverurl}/CDN/teamdesign9/images/left-pattern.png" alt=""><img class="bg-right-pattern" src="${Vvveb.serverurl}/CDN/teamdesign9/images/right-pattern.png" alt=""></div></div><div class="carousel-item edw-slider-item" data-value="2"><div class="card item-container"><div class="card-left"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/teamdesign9/images/slider-image/image-1.png" alt="image-1"></div><div class="card-right"><div class="edw-carousel-content"><h1 class="edw-carousel-content-heading">Meet Our expert faculty</h1><p class="edw-carousel-content-para">Our faculty includes seasoned doctors, specialists, and educators committed to your success.</p></div><div class="profile-navigator"><div class="profile"><p class="testimonial-user-name">Shina William</p><p class="testimonial-user-desg">MBBS, MD – Senior Medical Trainer</p></div></div></div><img class="bg-big-circle" src="${Vvveb.serverurl}/CDN/teamdesign9/images/big-circle.png" alt=""><img class="bg-small-circle" src="${Vvveb.serverurl}/CDN/teamdesign9/images/small-circle.png" alt=""><img class="bg-left-pattern" src="${Vvveb.serverurl}/CDN/teamdesign9/images/left-pattern.png" alt=""><img class="bg-right-pattern" src="${Vvveb.serverurl}/CDN/teamdesign9/images/right-pattern.png" alt=""></div></div><div class="carousel-item edw-slider-item" data-value="3"><div class="card item-container"><div class="card-left"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/teamdesign9/images/slider-image/image-1.png" alt="image-1"></div><div class="card-right"><div class="edw-carousel-content"><h1 class="edw-carousel-content-heading">Meet Our expert faculty</h1><p class="edw-carousel-content-para">Our faculty includes seasoned doctors, specialists, and educators committed to your success.</p></div><div class="profile-navigator"><div class="profile"><p class="testimonial-user-name">Shina William</p><p class="testimonial-user-desg">MBBS, MD – Senior Medical Trainer</p></div></div></div><img class="bg-big-circle" src="${Vvveb.serverurl}/CDN/teamdesign9/images/big-circle.png" alt=""><img class="bg-small-circle" src="${Vvveb.serverurl}/CDN/teamdesign9/images/small-circle.png" alt=""><img class="bg-left-pattern" src="${Vvveb.serverurl}/CDN/teamdesign9/images/left-pattern.png" alt=""><img class="bg-right-pattern" src="${Vvveb.serverurl}/CDN/teamdesign9/images/right-pattern.png" alt=""></div></div><div class="carousel-item edw-slider-item" data-value="4"><div class="card item-container"><div class="card-left"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/teamdesign9/images/slider-image/image-1.png" alt="image-1"></div><div class="card-right"><div class="edw-carousel-content"><h1 class="edw-carousel-content-heading">Meet Our expert faculty</h1><p class="edw-carousel-content-para">Our faculty includes seasoned doctors, specialists, and educators committed to your success.</p></div><div class="profile-navigator"><div class="profile"><p class="testimonial-user-name">Shina William</p><p class="testimonial-user-desg">MBBS, MD – Senior Medical Trainer</p></div></div></div><img class="bg-big-circle" src="${Vvveb.serverurl}/CDN/teamdesign9/images/big-circle.png" alt=""><img class="bg-small-circle" src="${Vvveb.serverurl}/CDN/teamdesign9/images/small-circle.png" alt=""><img class="bg-left-pattern" src="${Vvveb.serverurl}/CDN/teamdesign9/images/left-pattern.png" alt=""><img class="bg-right-pattern" src="${Vvveb.serverurl}/CDN/teamdesign9/images/right-pattern.png" alt=""></div></div><div class="navigator-buttons"><ol class="carousel-indicators edw-carousel-indicators"><li data-target="#edw_testimonial_unqreplaceid_" data-bs-target="#edw_testimonial_unqreplaceid_" data-slide-to="0" data-bs-slide-to="0" class="active"></li><li data-target="#edw_testimonial_unqreplaceid_" data-bs-target="#edw_testimonial_unqreplaceid_" data-slide-to="1" data-bs-slide-to="1" class=""></li><li data-target="#edw_testimonial_unqreplaceid_" data-bs-target="#edw_testimonial_unqreplaceid_" data-slide-to="2" data-bs-slide-to="2" class=""></li><li data-target="#edw_testimonial_unqreplaceid_" data-bs-target="#edw_testimonial_unqreplaceid_" data-slide-to="3" data-bs-slide-to="3" class=""></li><li data-target="#edw_testimonial_unqreplaceid_" data-bs-target="#edw_testimonial_unqreplaceid_" data-slide-to="4" data-bs-slide-to="4" class=""></li></ol><button class="carousel-control-next edw-slide-control edw-control-next" type="button" data-target="#edw_testimonial_unqreplaceid_" data-bs-target="#edw_testimonial_unqreplaceid_" data-slide="next" data-bs-slide="next"><img class="next-icon" src="${Vvveb.serverurl}/CDN/teamdesign9/images/right-arrow.png" alt="" area-hidden='true'></button></div></div><input name="navigationbutton" class="form-check-input edw-slider-navigationbutton" type="checkbox" checked="checked" style="display:none!important"><input name="navigationbutton" class="form-check-input edw-slider-navigationbullets" type="checkbox" checked="checked" style="display:none!important"><input name="navigationbutton" class="form-check-input edw-slider-autoplay" type="checkbox" checked="checked" style="display:none!important"><input name="navigationbutton" class="form-check-input edw-slider-pauseonhover" type="checkbox" checked="checked" style="display:none!important"></div></section>`;
        var slidercss16 = `.medical-block-3.section-testimonial-design_unqreplaceid_ h1,.medical-block-3.section-testimonial-design_unqreplaceid_ h2,.medical-block-3.section-testimonial-design_unqreplaceid_ p{margin:0}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container{max-width:1240px;margin:0 auto;font-family:Inter;padding:120px 0}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container{width:100%;background-color:#1f3323;border-radius:20px;padding:69.5px 6.2403%;border:none!important;flex-direction:row;position:relative}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-left{position:static!important}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-left img{position:absolute;bottom:0;left:11.0032%;z-index:1}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right{margin-left:auto;width:55%;display:flex;flex-direction:column;gap:42px;z-index:1}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .edw-carousel-content{width:100%;display:flex;flex-direction:column;gap:16px}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .edw-carousel-content .edw-carousel-content-heading{font-weight:300;font-size:45px;line-height:100%;letter-spacing:0;color:#d0ff97}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .edw-carousel-content .edw-carousel-content-para{font-weight:400;font-size:18px;line-height:22px;letter-spacing:0;color:#fff}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .profile-navigator{width:100%;display:flex;align-items:end;justify-content:space-between;gap:22px}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .profile-navigator .profile{display:flex;flex-direction:column;gap:6px;padding:20px 25px;background:linear-gradient(90deg,#354d3d 0,#213426 100%);border-radius:10px}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .profile-navigator .profile .testimonial-user-name{font-weight:300;font-size:26px;line-height:100%;letter-spacing:0;color:#fff}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .profile-navigator .profile .testimonial-user-desg{font-weight:400;font-size:16px;line-height:22px;letter-spacing:0;color:#fff}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right-mobile{display:none}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .bg-left-pattern{position:absolute;top:0;left:0;z-index:0;height:100%}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .bg-right-pattern{position:absolute;top:0;right:0;z-index:0}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .bg-small-circle{position:absolute;bottom:30px;z-index:0}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .bg-big-circle{position:absolute;left:15.07822%;bottom:22%;z-index:0;opacity:.5}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .navigator-buttons{position:absolute;right:6.2403%;bottom:189.5px;display:flex;gap:18px;align-items:center}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .navigator-buttons .carousel-indicators{position:static!important;width:fit-content;height:fit-content;padding:0;margin:0;gap:10px!important}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .navigator-buttons .carousel-indicators li{opacity:1;background-color:#e3e8e5;border:1px solid #e3e8e5;width:5px;height:5px;border-radius:50%;margin:0;cursor:pointer}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .navigator-buttons .carousel-indicators .active{background-color:#39b54a;border:1px solid #39b54a}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .navigator-buttons .carousel-control-next{position:static!important;width:fit-content;opacity:1!important}@media screen and (max-width:1320px){.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container{max-width:95%}}@media screen and (max-width:1320px) and (min-width:1085px){.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container{padding:140px 0}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .navigator-buttons{bottom:209.5px}}@media screen and (max-width:1088px){.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-left img{left:6.44829%}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right{width:60%}}@media screen and (max-width:976px){.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container{padding:70px 0}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container{padding:50px 6.2403%}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .edw-carousel-content{gap:10px}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .edw-carousel-content .edw-carousel-content-heading{font-size:32px}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .profile-navigator .profile{padding:12px 25px;gap:4px}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .profile-navigator .profile .testimonial-user-name{font-size:22px}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .profile-navigator .profile .testimonial-user-desg{font-size:12px;line-height:100%}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-left img{height:350px;width:230.2px}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .bg-left-pattern{width:273.41px;height:100%;bottom:0;left:0;top:unset}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .bg-right-pattern{width:148.71px;height:165.54px}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .bg-small-circle{width:71.06px;height:71.06px}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .bg-big-circle{width:138.73px;height:138.73px}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .navigator-buttons{bottom:120px}}@media screen and (max-width:767.8px){.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container{background-color:#1f3323;width:100%;flex-direction:column;gap:0;padding:286px 30px 92px}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right{width:100%;display:flex;flex-direction:column-reverse;gap:30px;justify-content:center}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .edw-carousel-content{width:100%;display:flex;flex-direction:column;gap:16px}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .edw-carousel-content .edw-carousel-content-heading{font-weight:300;font-size:32px;line-height:100%;letter-spacing:0;color:#d0ff97}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .edw-carousel-content .edw-carousel-content-para{font-weight:400;font-size:18px;line-height:22px;letter-spacing:0;color:#fff}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .profile-navigator{width:100%}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .profile-navigator .profile{width:100%;display:flex;flex-direction:column;gap:6px;padding:20px 25px;background:linear-gradient(180deg,#354d3d 0,#273b2c 100%);border-radius:10px;z-index:2}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .profile-navigator .profile .testimonial-user-name{font-weight:300;font-size:22px;line-height:100%;letter-spacing:0;color:#fff}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .profile-navigator .profile .testimonial-user-desg{font-weight:400;font-size:12px;line-height:100%;letter-spacing:0;color:#fff}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-left{width:100%;display:flex;justify-content:center}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-left img{bottom:unset;left:auto;right:auto;top:-65px}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .bg-left-pattern{top:140px;width:138px;height:115.35px}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .bg-small-circle{top:200px;left:15%}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .bg-big-circle{display:none}.medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .navigator-buttons{bottom:110px;left:50%;transform:translateX(-50%);width:fit-content}}li::marker{content:""}.card-left img.edw-profile-img{width:327.05px;height:473.01px}.profile-navigator .profile{width:54%}@media screen and (min-width:768px){.edw-limitedwidth-block .medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container{padding:70px 0}.edw-limitedwidth-block .medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container{padding:50px 6.2403%}.edw-limitedwidth-block .medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .edw-carousel-content{gap:10px}.edw-limitedwidth-block .medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .edw-carousel-content .edw-carousel-content-heading{font-size:32px}.edw-limitedwidth-block .medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .profile-navigator .profile{padding:12px 25px;gap:4px}.edw-limitedwidth-block .medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .profile-navigator .profile .testimonial-user-name{font-size:22px}.edw-limitedwidth-block .medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-right .profile-navigator .profile .testimonial-user-desg{font-size:12px;line-height:100%}.edw-limitedwidth-block .medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .card-left img{height:350px;width:230.2px}.edw-limitedwidth-block .medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .bg-left-pattern{width:273.41px;height:100%;bottom:0;left:0;top:unset}.edw-limitedwidth-block .medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .bg-right-pattern{width:148.71px;height:165.54px}.edw-limitedwidth-block .medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .bg-small-circle{width:71.06px;height:71.06px}.edw-limitedwidth-block .medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .carousel-item.edw-slider-item .card.item-container .bg-big-circle{width:138.73px;height:138.73px}.edw-limitedwidth-block .medical-block-3.section-testimonial-design_unqreplaceid_ #carousel_container.edw-slider-inner-container .navigator-buttons{bottom:120px}}ol{list-style-type:none!important}`;
        var sliderjs16 = ``;
        var appendnode16 = `<div class="carousel-item edw-slider-item" data-value="0"><div class="card item-container"><div class="card-left"><img class="edw-profile-img" src="${Vvveb.serverurl}/CDN/teamdesign9/images/slider-image/image-1.png" alt="image-1"></div><div class="card-right"><div class="edw-carousel-content"><h1 class="edw-carousel-content-heading">Meet Our expert faculty</h1><p class="edw-carousel-content-para">Our faculty includes seasoned doctors, specialists, and educators committed to your success.</p></div><div class="profile-navigator"><div class="profile"><p class="testimonial-user-name">Shina William</p><p class="testimonial-user-desg">MBBS, MD – Senior Medical Trainer</p></div></div></div><img class="bg-big-circle" src="${Vvveb.serverurl}/CDN/teamdesign9/images/big-circle.png" alt=""><img class="bg-small-circle" src="${Vvveb.serverurl}/CDN/teamdesign9/images/small-circle.png" alt=""><img class="bg-left-pattern" src="${Vvveb.serverurl}/CDN/teamdesign9/images/left-pattern.png" alt=""><img class="bg-right-pattern" src="${Vvveb.serverurl}/CDN/teamdesign9/images/right-pattern.png" alt=""></div></div>`;
        Vvveb.Components.extend("_base", "html/slider16", {
            name: "Profile slider 4",
            attributes: ['data-ebpb-slider16'],
            image: "icons/profileslider4.svg",
            classes: ['edwiser-pb-slider16'],
            html: (() => {
                return `<div class="edwiser-pb-slider16" data-vvveb-disabled-area contenteditable="false">${sliderhtml16}<style>${slidercss16}</style></div>`;
            })(),
            beforeInit: function (node) {
                properties = [];
                var i = 0;
                var slideno = 0;
                var id = generateUniqueID();
                node.innerHTML = node.innerHTML.replaceAll("_unqreplaceid_", id);
                $(node).find(".carousel-item").each(function (e) {
                    i = generateUniqueID();
                    $(this).attr("data-value", slideno);
                    slideno++;
                    var regex = /edw-carousel-item-\d+/;
                    var matchedClass = "";
                    var hasMatchingClass = $(this).filter(function () {
                        var classNames = $(this).attr('class').split(' ');
                        return classNames.some(function (className) {
                            if (regex.test(className)) {
                                matchedClass = className;
                                return true;
                            }
                            return false;
                        });
                    }).length > 0;
                    $(this).removeClass(matchedClass);
                    $(this).addClass("edw-carousel-item-" + i);

                    properties.push(
                        {
                            name: "",
                            key: "slidergrouptitle" + slideno,
                            inputtype: EdwheaderInput,
                            edwclasses: "edwgroupheader",
                            data: {
                                header: "Profile " + slideno,
                                extraclass: "edwslideheading m-0",
                                type: "h6",
                                style: ""
                            }
                        },
                        {
                            name: "",
                            key: "deleteslideritem",
                            inputtype: EdwbuttonInput,
                            child: `.edw-carousel-item-${i}`,
                            edwclasses: "edwslidedelbtn",
                            data: { text: "", icon: "la-trash", extraclasses: "btn btn-outline-danger" },
                            onChange: function (node, value, input) {
                                $(node).remove();
                                Vvveb.Components.render("html/slider16");
                                return node;
                            },
                        },
                        {
                            name: SETTINGTITLES.TITLE,
                            key: "slidertitle" + i,
                            htmlAttr: "innerHTML",
                            child: `.edw-carousel-item-${i} .edw-carousel-content-heading`,
                            inputtype: TextInput,
                            edwclasses: "edwinputfield",
                            onChange: function (node, value, input) {
                                if (value == "") {
                                    $(node).hide();
                                } else {
                                    $(node).show().text(value);
                                }
                                return node;
                            }
                        },
                        {
                            name: SETTINGTITLES.DESCRIPTION,
                            key: "sliderdescription" + i,
                            htmlAttr: "innerHTML",
                            child: `.edw-carousel-item-${i} .edw-carousel-content-para`,
                            inputtype: TextInput,
                            edwclasses: "edwinputfield",
                            onChange: function (node, value, input) {
                                if (value == "") {
                                    $(node).hide();
                                } else {
                                    $(node).show().text(value);
                                }
                                return node;
                            }
                        },
                        {
                            name: SETTINGTITLES.FACULTYNAME,
                            key: "sliderfacultyname" + i,
                            htmlAttr: "innerHTML",
                            child: `.edw-carousel-item-${i} .testimonial-user-name`,
                            inputtype: TextInput,
                            edwclasses: "edwinputfield",
                            onChange: function (node, value, input) {
                                if (value == "") {
                                    $(node).hide();
                                } else {
                                    $(node).show().text(value);
                                }
                                return node;
                            }
                        },
                        {
                            name: SETTINGTITLES.FACULTYDESIGNATION,
                            key: "sliderfacultydesignation" + i,
                            htmlAttr: "innerHTML",
                            child: `.edw-carousel-item-${i} .testimonial-user-desg`,
                            inputtype: TextInput,
                            edwclasses: "edwinputfield",
                            onChange: function (node, value, input) {
                                if (value == "") {
                                    $(node).hide();
                                } else {
                                    $(node).show().text(value);
                                }
                                return node;
                            }
                        },
                        {
                            name: SETTINGTITLES.IMAGE,
                            key: "sliderimage" + i,
                            htmlAttr: 'src',
                            inputtype: ImageInput,
                            edwclasses: "edwfilefield",
                            child: `.edw-carousel-item-${i} .edw-profile-img`,
                        }
                    );
                });

                properties = removeDeleteButton(node, properties, 1);
                removeSettingsOnSingleSlide(node);
                if (!$(node).find('.edw-slider-inner-container').children('.edw-slider-item').hasClass('active')) {
                    $(node).find('.edw-slider-item').first().addClass('active');
                }
                removeDuplicateIndicators(node, i);

                //remove all option properties
                this.properties = this.properties.filter(function (item) {
                    return item.key.indexOf("slider") === -1;
                });
                //remove all option properties
                this.properties = this.properties.filter(function (item) {
                    return item.key.indexOf("color") === -1;
                });
                this.properties = properties.concat(this.properties);
                this.properties = disableaddnewslidebutton(node,this.properties, 10);
                slideIntervalfielddisabler(node);
                return node;
            },
            properties: [
                {
                    name: "",
                    key: "tabswarning",
                    inputtype: EdwheaderInput,
                    edwclasses: "edwgroupheader",
                    data: {
                        header: "Only 10 profiles are allowed",
                        extraclass: "edwslideheading m-0 p-3 border-0 alert alert-warning",
                        type: "h6",
                        style: ""
                    }
                },
                {
                    name: "",
                    key: "addNewSlide",
                    inputtype: EdwbuttonInput,
                    edwclasses: "edwnewslidebtn",
                    data: { text: "Add new slide", icon: "la-plus", extraclasses: "btn btn-outline-primary" },
                    onChange: function (node) {
                        //render component properties again to include the new column inputs
                        $(node).parent().find('.carousel-inner').append(appendnode16);
                        Vvveb.Components.render("html/slider16");
                        return node;
                    }
                },
                {
                    name: SETTINGTITLES.SHOWNAVIGATIONBUTTONS,
                    key: "navigationbutton",
                    htmlAttr: "checked",
                    col: 12,
                    inline: true,
                    inputtype: CheckboxInput,
                    edwclasses: "edwcheckfield",
                    child: '.edw-slider-navigationbutton',
                    onChange: function (node, value, input) {
                        if (value == true) {
                            // $(node).parent().find('.edw-control-prev').removeClass('d-none');
                            $(node).parent().find('.edw-control-next').removeClass('d-none');
                        } else {
                            // $(node).parent().find('.edw-control-prev').addClass('d-none');
                            $(node).parent().find('.edw-control-next').addClass('d-none');
                        }
                        var tempnode = $(node).closest('.edw-carousel')
                        // slidearrowcolorsettingstatushandler(tempnode,arrow=false, arrowandbullet = true, title = SETTINGTITLES.ARROWASSETCOLORINFOWITHBULLETS);
                        return node;
                    }
                },
                {
                    name: SETTINGTITLES.SHOWNAVIGATIONBULLETS,
                    key: "navigationbullets",
                    htmlAttr: "checked",
                    col: 12,
                    inline: true,
                    inputtype: CheckboxInput,
                    edwclasses: "edwcheckfield",
                    child: '.edw-slider-navigationbullets',
                    onChange: function (node, value, input) {
                        if (value == true) {
                            $(node).parent().find('.edw-carousel-indicators').removeClass('d-none');
                        } else {
                            $(node).parent().find('.edw-carousel-indicators').addClass('d-none');
                        }
                        var tempnode = $(node).closest('.edw-carousel')
                        // slidearrowcolorsettingstatushandler(tempnode,arrow=false, arrowandbullet = true, title = SETTINGTITLES.ARROWASSETCOLORINFOWITHBULLETS);
                        return node;
                    }
                },
                {
                    name: SETTINGTITLES.AUTOPLAYSLIDES,
                    key: "autoplayslides",
                    htmlAttr: "checked",
                    col: 12,
                    inline: true,
                    inputtype: CheckboxInput,
                    edwclasses: "edwcheckfield",
                    child: '.edw-slider-autoplay',
                    onChange: function (node, value, input) {
                        var slideintervalfield = $(input).closest('section').find('[data-key="slideinterval"]');
                        if (value == true) {
                            $(node).parent().attr('data-ride', 'carousel');
                            $(node).parent().attr('data-interval', '3000');
                            $(node).parent().attr('data-bs-ride', 'carousel');
                            $(node).parent().attr('data-bs-interval', '3000');
                            slideintervalfield.find('input[name="slideinterval"]').val('3000');
                        } else {
                            $(node).parent().removeAttr('data-ride');
                            $(node).parent().attr('data-interval', '0');
                            $(node).parent().removeAttr('data-bs-ride');
                            $(node).parent().attr('data-bs-interval', '0');
                            slideintervalfield.find('input[name="slideinterval"]').val('0');
                        }
                        slideIntervalfielddisabler(node);
                        return node;
                    }
                },
                {
                    name: SETTINGTITLES.PAUSESLIDESONHOVER,
                    key: "pauseslides",
                    htmlAttr: "checked",
                    col: 12,
                    inline: true,
                    inputtype: CheckboxInput,
                    edwclasses: "edwcheckfield",
                    child: '.edw-slider-pauseonhover',
                    onChange: function (node, value, input) {
                        if (value == true) {
                            $(node).parent().attr('data-pause', 'hover');
                            $(node).parent().attr('data-bs-pause', 'hover');
                        } else {
                            $(node).parent().attr('data-pause', 'false');
                            $(node).parent().attr('data-bs-pause', 'false');
                        }
                        return node;
                    }
                },
                {
                    name: SETTINGTITLES.SLIDEINTERVAL,
                    key: "slideinterval",
                    htmlAttr: "data-interval",
                    inputtype: TextInput,
                    edwclasses: "edwinputfield",
                    child: `.carousel`,
                    onChange: function (node, value, input) {

                        $(node).attr('data-interval', value);
                        $(node).attr('data-bs-interval', value);

                        return node;
                    }
                }
            ]
        });

        // Profile Slider 5  --> Team Design - 10
        var profilesliderhtml5 = `<section class="safety-block-3"><div class="section-container"><div class="top-content-mobile"><p class="title"> Instructors </p><h2 class="desc"> Trained experts behind 3800+ lives saved </h2></div><div class="section-testimonial-design_unqreplaceid_"><div class="carousel wrapper edw-carousel" data-interval="3000" data-pause="hover" data-ride="carousel"><div class="slider edw-slider-inner-container"><div class="slide edw-slider-item active" data-value="0"><div class="card"><img class="card-image" src="${Vvveb.serverurl}/CDN/teamdesign10/images/image1.png" alt=""><div class="card-content"><h2>Jacob Anderson</h2><p>Fire & Life Safety Coordinator</p></div></div></div><div class="slide edw-slider-item" data-value="1"><div class="card"><img class="card-image" src="${Vvveb.serverurl}/CDN/teamdesign10/images/image2.png" alt=""><div class="card-content"><h2>Maria Lipton</h2><p>Fire Prevention Specialist</p></div></div></div><div class="slide edw-slider-item " data-value="2"><div class="card"><img class="card-image" src="${Vvveb.serverurl}/CDN/teamdesign10/images/image3.png" alt=""><div class="card-content"><h2>Pierre Malcon</h2><p>Fire Safety Officer</p></div></div></div><div class="slide edw-slider-item" data-value="3"><div class="card"><img class="card-image" src="${Vvveb.serverurl}/CDN/teamdesign10/images/image1.png" alt=""><div class="card-content"><h2>Jacob Anderson</h2><p>Fire & Life Safety Coordinator</p></div></div></div><div class="slide edw-slider-item" data-value="4"><div class="card"><img class="card-image" src="${Vvveb.serverurl}/CDN/teamdesign10/images/image2.png" alt=""><div class="card-content"><h2>Maria Lipton</h2><p>Fire Prevention Specialist</p></div></div></div><div class="slide edw-slider-item" data-value="5"><div class="card"><img class="card-image" src="${Vvveb.serverurl}/CDN/teamdesign10/images/image3.png" alt=""><div class="card-content"><h2>Pierre Malcon</h2><p>Fire Safety Officer</p></div></div></div><div class="slide edw-slider-item" data-value="6"><div class="card"><img class="card-image" src="${Vvveb.serverurl}/CDN/teamdesign10/images/image1.png" alt=""><div class="card-content"><h2>Jacob Anderson</h2><p>Fire & Life Safety Coordinator</p></div></div></div><div class="slide edw-slider-item" data-value="7"><div class="card"><img class="card-image" src="${Vvveb.serverurl}/CDN/teamdesign10/images/image2.png" alt=""><div class="card-content"><h2>Maria Lipton</h2><p>Fire Prevention Specialist</p></div></div></div></div></div><input name="navigationbutton" class="form-check-input edw-slider-navigationbutton" type="checkbox" checked="checked" style="display:none!important"><input name="navigationbutton" class="form-check-input edw-slider-navigationbullets" type="checkbox" checked="checked" style="display:none!important"><input name="navigationbutton" class="form-check-input edw-slider-autoplay" type="checkbox" checked="checked" style="display:none!important"><input name="navigationbutton" class="form-check-input edw-slider-pauseonhover" type="checkbox" checked="checked" style="display:none!important"></div><div class="right"><div class="right-content"><p class="title"> Instructors </p><h2 class="desc"> Trained experts behind 3800+ lives saved </h2></div><div class="slide-navigation"><div class="slider-arrow "><button class="left edw-slide-control edw-control-prev"><img class="left-arrow-icon" src="${Vvveb.serverurl}/CDN/teamdesign10/images/left-arrow.svg" alt=""><img class="hover-left-arrow" src="${Vvveb.serverurl}/CDN/teamdesign10/images/hover-left-arrow.svg" alt=""></button></div><div class="indicators edw-carousel-indicators"><span class="indicator-btn active" data-value="0"></span><span class="indicator-btn" data-value="1"></span><span class="indicator-btn" data-value="2"></span><span class="indicator-btn" data-value="3"></span><span class="indicator-btn" data-value="4"></span><span class="indicator-btn" data-value="5"></span><span class="indicator-btn" data-value="6"></span><span class="indicator-btn" data-value="7"></span></div><div class="slider-arrow "><button class="right edw-slide-control edw-control-next"><img class="right-arrow-icon" src="${Vvveb.serverurl}/CDN/teamdesign10/images/right-arrow.svg" alt=""><img class="hover-right-arrow" src="${Vvveb.serverurl}/CDN/teamdesign10/images/hover-right-arrow.svg" alt=""></button></div></div></div><div class="background"><img src="${Vvveb.serverurl}/CDN/teamdesign10/images/right-pattern.png" alt=""></div></div></section>`;
        var profileslidercss5 = `.safety-block-3{overflow-x:hidden;width:100%}.safety-block-3 *{box-sizing:border-box}.safety-block-3 h1,.safety-block-3 h2,.safety-block-3 h3,.safety-block-3 p{margin:0}.safety-block-3 .section-container{width:100%;padding:102.22px 0;font-family:Inter;position:relative}.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-next:hover .right-arrow-icon,.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-prev:hover .left-arrow-icon,.safety-block-3 .section-container .top-content-mobile{display:none}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_{background:0 0;width:66%;max-width:1160px;position:absolute;left:0;bottom:102.22px;z-index:1}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider{width:100%;height:525.46px;position:relative;overflow:hidden}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .slide{width:344px;height:430px;position:absolute;right:-408px;bottom:0;transition:none}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .card{height:100%;border-radius:0 0 10px 10px;position:relative;background-color:#fff;padding:333.1px 24px 24px;box-shadow:0 34px 26px 0 rgba(0,0,0,.1);border:1px solid transparent;background:linear-gradient(white,#fff) padding-box,linear-gradient(180deg,#fff 0,rgba(0,0,0,.2) 100%) border-box}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .card .card-image{width:293.26px;height:428.55px;display:block;object-fit:contain;position:absolute;bottom:96.9px;border-bottom:1px solid #e4e4e4}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .card .card-content{padding-top:14px;display:flex;flex-direction:column;align-items:center;text-align:center;width:100%}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .card .card-content h2{font-weight:300;font-size:24px;color:#383546}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .card .card-content p{font-weight:400;font-size:16px;line-height:22px;color:#4b5563}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .slide.prev-prev{right:816px;left:unset}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .slide.prev{right:408px;left:unset}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .slide:not(.active):after{content:"";position:absolute;border-radius:10px;bottom:0;left:0;width:100%;height:525.46px;background-image:linear-gradient(0deg,rgba(255,255,255,.8) 0,rgba(255,255,255,.8) 100%)}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .slide.active{left:unset;right:0}@keyframes activeToPrev{from{right:0}to{right:408px}}@keyframes prevToPrevPrev{from{right:408px}to{right:816px}}@keyframes enterActiveFromRight{from{right:-408px;opacity:0}to{right:0;opacity:1}}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .slide.animate-active-to-prev{animation:.5s forwards activeToPrev}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .slide.animate-prev-to-prevprev{animation:.5s forwards prevToPrevPrev}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .slide.animate-enter-active{animation:.5s forwards enterActiveFromRight}@keyframes activeToNext{from{right:0}to{right:-408px;opacity:0}}@keyframes enterActiveFromLeft{from{right:408px;opacity:0}to{right:0;opacity:1}}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .slide.animate-active-to-next{animation:.5s forwards activeToNext}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .slide.animate-enter-active-from-left{animation:.5s forwards enterActiveFromLeft}@keyframes prevPrevExitRight{from{right:816px;opacity:1}to{right:1224px;opacity:0}}@keyframes prevPrevPrevExitLeft{from{right:1224px;opacity:0}to{right:816px;opacity:1}}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .slide.animate-prev-prev-exit{animation:.5s forwards prevPrevExitRight}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .slide.animate-prev-prev-prev-enter{animation:.5s forwards prevPrevPrevExitLeft}@keyframes prevPrevToPrev{from{right:816px}to{right:408px}}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .slide.animate-prev-prev-to-prev{animation:.5s forwards prevPrevToPrev}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider.dragging{cursor:grab}.safety-block-3 .section-container .right:not(.edw-slide-control){margin-left:calc(66% + 60px);width:fit-content;height:429px;display:flex;flex-direction:column;justify-content:space-between;align-items:start}.safety-block-3 .section-container .right:not(.edw-slide-control) .right-content{display:flex;flex-direction:column;gap:16px;max-width:306.54px;z-index:1}.safety-block-3 .section-container .right:not(.edw-slide-control) .right-content p{font-weight:600;font-size:16px;color:#f72608}.safety-block-3 .section-container .right:not(.edw-slide-control) .right-content h2{font-weight:300;font-size:40px;color:#062f65;max-height:144px;overflow-y:auto;scrollbar-color:#d5ddea transparent}.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation{display:flex;gap:16px;align-items:center;width:fit-content;z-index:1}.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-next,.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-prev{cursor:pointer;display:flex;justify-content:center;align-items:center;width:50px;height:50px;border:1px solid #f72608;border-radius:4px;font-size:24px;background-color:#fff}.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-next .hover-right-arrow,.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-prev .hover-left-arrow{display:none;width:19.84px;height:10.75px}.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-next .right-arrow-icon,.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-prev .left-arrow-icon{display:block;width:19.84px;height:10.75px}.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-next:hover,.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-prev:hover{background-color:#f72608;border:1px solid #fff}.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-next:hover .hover-right-arrow,.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-prev:hover .hover-left-arrow{display:block}.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-carousel-indicators{display:flex;gap:12px}.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-carousel-indicators .indicator-btn{width:8px;height:8px;border-radius:100%;background-color:#e4e4e4;cursor:pointer}.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-carousel-indicators .indicator-btn.active{background-color:#f72608}.safety-block-3 .section-container .background{width:100vw;height:307.61px;position:absolute;bottom:0;left:0;background-color:#3d111d;z-index:0}.safety-block-3 .section-container .background img{position:absolute;bottom:0;right:0;display:block;width:35.2%;height:100%}.edwiser-pb-profileslider5{width:100%;box-sizing:border-box;background-color:#fff}@media screen and (max-width:1400px){.safety-block-3 .section-container{padding:102.22px 0}}@media screen and (max-width:1245px){.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_{width:60%}.safety-block-3 .section-container .right:not(.edw-slide-control){margin-left:calc(60% + 60px)}}@media screen and (max-width:1024px){.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-next,.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-next:hover,.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-prev{background-color:#f72608;border:1px solid #fff}.safety-block-3 .section-container{display:flex;padding:102.22px 3.906%;align-items:end;justify-content:center;gap:60px}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_{width:344px;position:static;box-shadow:0 170px 26px 0 rgba(0,0,0,.1)}.safety-block-3 .section-container .right:not(.edw-slide-control){margin-left:unset}.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-next .hover-right-arrow,.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-next:hover .hover-right-arrow,.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-prev .hover-left-arrow,.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-prev:hover .hover-left-arrow{display:block}.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-next .right-arrow-icon,.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-next:hover .right-arrow-icon,.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-prev .left-arrow-icon,.safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-prev:hover .left-arrow-icon{display:none}}@media screen and (max-width:815px){.safety-block-3 .section-container{padding:102.22px 15px}.safety-block-3 .section-container .right:not(.edw-slide-control){width:fit-content}}@media screen and (max-width:734px){.safety-block-3 .section-container{padding:64px 15px;flex-direction:column;gap:34px;align-items:center}.safety-block-3 .section-container .top-content-mobile{display:flex;flex-direction:column;gap:16px;max-width:306.54px;z-index:1}.safety-block-3 .section-container .top-content-mobile p{font-weight:600;font-size:16px;color:#f72608}.safety-block-3 .section-container .top-content-mobile h2{font-weight:300;font-size:40px;color:#062f65}.safety-block-3 .section-container .right:not(.edw-slide-control){height:fit-content;display:block;z-index:1}.safety-block-3 .section-container .right:not(.edw-slide-control) .right-content{display:none}}@media screen and (max-width:400px){.safety-block-3 .section-container .top-content-mobile{max-width:100%}}@media screen and (max-width:370px){.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_{width:100%}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .slide{width:100%;height:auto}.safety-block-3 .section-container .section-testimonial-design_unqreplaceid_ .slider .slide .card .card-image{width:85.25%;height:auto;aspect-ratio:293.26/428.55px}}@media screen and (min-width:1024px){.edw-limitedwidth-block .safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-next,.edw-limitedwidth-block .safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-next:hover,.edw-limitedwidth-block .safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-prev{background-color:#f72608;border:1px solid #fff}.edw-limitedwidth-block .safety-block-3 .section-container{display:flex;padding:102.22px 3.906%;align-items:end;justify-content:center;gap:60px}.edw-limitedwidth-block .safety-block-3 .section-container .section-testimonial-design_unqreplaceid_{width:344px;position:static;box-shadow:0 170px 26px 0 rgba(0,0,0,.1)}.edw-limitedwidth-block .safety-block-3 .section-container .right:not(.edw-slide-control){margin-left:unset}.edw-limitedwidth-block .safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-next .hover-right-arrow,.edw-limitedwidth-block .safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-next:hover .hover-right-arrow,.edw-limitedwidth-block .safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-prev .hover-left-arrow,.edw-limitedwidth-block .safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-prev:hover .hover-left-arrow{display:block}.edw-limitedwidth-block .safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-next .right-arrow-icon,.edw-limitedwidth-block .safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-next:hover .right-arrow-icon,.edw-limitedwidth-block .safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-prev .left-arrow-icon,.edw-limitedwidth-block .safety-block-3 .section-container .right:not(.edw-slide-control) .slide-navigation .edw-control-prev:hover .left-arrow-icon{display:none}}@media screen and (max-width:1000px) and (min-width:733px){.edw-limitedwidth-block .safety-block-3 .section-container{padding:64px 15px;flex-direction:column;gap:34px;align-items:center}.edw-limitedwidth-block .safety-block-3 .section-container .top-content-mobile{display:flex;flex-direction:column;gap:16px;max-width:306.54px;z-index:1}.edw-limitedwidth-block .safety-block-3 .section-container .top-content-mobile p{font-weight:600;font-size:16px;color:#f72608}.edw-limitedwidth-block .safety-block-3 .section-container .top-content-mobile h2{font-weight:300;font-size:40px;color:#062f65}.edw-limitedwidth-block .safety-block-3 .section-container .right:not(.edw-slide-control){height:fit-content;display:block;z-index:1}.edw-limitedwidth-block .safety-block-3 .section-container .right:not(.edw-slide-control) .right-content{display:none}}`;
        var profilesliderjs5 = 'class TestimonialSafetyDesign3_unqreplaceid_{constructor(){this.testimonial=document.querySelector(".section-testimonial-design_unqreplaceid_"),this.container=document.querySelector(".safety-block-3"),this.leftArrow=this.container.querySelector(".slider-arrow .left"),this.rightArrow=this.container.querySelector(".slider-arrow .right"),this.indicators=this.container.querySelectorAll(".indicators .indicator-btn"),this.carousel=this.testimonial.querySelector(".carousel"),this.slider=this.testimonial.querySelector(".slider"),this.sliderLength=this.slider.querySelectorAll(".slide").length,this.resizeTimer,this.initialExecution=!0,this.isTouchStart=!1,this.startX=0,this.distance=0,this.touchTimeout=null,this.autoSlideTimer=null,this.isHover=!1,this.leftClick=this.leftClick.bind(this),this.rightClick=this.rightClick.bind(this),this.indicatorClick=this.indicatorClick.bind(this),this.touchStart=this.touchStart.bind(this),this.touchMove=this.touchMove.bind(this),this.touchStop=this.touchStop.bind(this),this.hoverStart=this.hoverStart.bind(this),this.hoverEnd=this.hoverEnd.bind(this),this.initializeEventListeners(),this.initializeSlide(),this.autoSlide(),this.carousel.querySelectorAll(".mediaplugin")&&this.carousel.querySelectorAll(".mediaplugin div").forEach((t=>{t.style.maxWidth="unset"}))}initializeEventListeners(){this.leftArrow.addEventListener("click",this.leftClick),this.rightArrow.addEventListener("click",this.rightClick),this.indicators.forEach((t=>{t.addEventListener("click",this.indicatorClick)})),this.carousel.addEventListener("touchstart",this.touchStart),this.carousel.addEventListener("touchmove",this.touchMove),this.carousel.addEventListener("touchend",this.touchStop),this.carousel.addEventListener("mouseenter",this.hoverStart),this.carousel.addEventListener("mouseleave",this.hoverEnd),window.addEventListener("resize",this.handleScreenResize)}initializeSlide(t=null,e="next"){const i=this.testimonial.querySelectorAll(".slide");t||(t=this.testimonial.querySelector(".slide.active")||i[0]);const s=this.testimonial.querySelector(".slide.active"),r=this.testimonial.querySelector(".slide.prev"),a=this.testimonial.querySelector(".slide.prev-prev"),l=a?.previousElementSibling||i[i.length-1];i.forEach((t=>{t.classList.remove("animate-active-to-prev","animate-prev-to-prevprev","animate-prev-prev-exit","animate-enter-active","animate-active-to-next","animate-enter-active-from-left","animate-prev-prev-prev-enter","animate-prev-prev-to-prev")})),"next"===e?(s&&s.classList.add("animate-active-to-prev"),r&&r.classList.add("animate-prev-to-prevprev"),a&&a.classList.add("animate-prev-prev-exit"),t!==s&&t.classList.add("animate-enter-active")):(s&&s.classList.add("animate-active-to-next"),r&&r.classList.add("animate-enter-active-from-left"),a&&a.classList.add("animate-prev-prev-to-prev"),l&&l.classList.add("animate-prev-prev-prev-enter")),setTimeout((()=>{i.forEach((t=>{t.classList.remove("active","prev","prev-prev","animate-active-to-prev","animate-prev-to-prevprev","animate-prev-prev-exit","animate-enter-active","animate-active-to-next","animate-enter-active-from-left","animate-prev-prev-prev-enter","animate-prev-prev-to-prev")})),t.classList.add("active");const e=t.previousElementSibling||i[i.length-1],s=e.previousElementSibling||i[i.length-1];e.classList.add("prev"),s.classList.add("prev-prev"),this.setIndicator(t.dataset.value)}),500)}resetSlideVideo(t){let e=t.querySelector("video");e&&(e.pause(),e.currentTime=0)}leftClick(){if(this.sliderLength>1){let t=this.testimonial.querySelector(".slide.active").previousElementSibling;t||(t=this.testimonial.querySelector(".slider .slide:last-child")),this.initializeSlide(t,"prev")}}rightClick(){if(this.sliderLength>1){let t=this.testimonial.querySelector(".slide.active").nextElementSibling;t||(t=this.testimonial.querySelector(".slider .slide:first-child")),this.initializeSlide(t,"next")}}setIndicator(t){this.indicators.forEach((e=>{e.classList.remove("active"),e.getAttribute("data-value")==t&&e.classList.add("active")}))}indicatorClick(t){let e=t.target.getAttribute("data-value"),i=this.container.querySelector(".indicators .indicator-btn.active").getAttribute("data-value"),s=this.testimonial.querySelector(`.slider [data-value="${e}"]`),r="left";for(let t=0;t<this.indicators.length;t++){let s=this.indicators[t];if(e==s.getAttribute("data-value"))break;if(i==s.getAttribute("data-value")){r="right";break}}this.initializeSlide(s,r)}hoverStart(){this.isHover=!0}hoverEnd(){this.isHover=!1}autoSlide(){const t=parseInt(this.carousel.dataset.interval,10)||3e3,e="hover"===this.carousel.dataset.pause;"carousel"===this.carousel.dataset.ride&&(this.autoSlideTimer&&clearInterval(this.autoSlideTimer),this.autoSlideTimer=setInterval((()=>{e&&this.isHover||this.rightClick()}),t))}touchStart(t){this.isHover=!0,this.isTouchStart=!0,this.startX=t.touches[0].clientX}touchMove(t){this.isHover=!0,this.isTouchStart&&(this.distance=t.touches[0].clientX-this.startX)}touchStop(){clearTimeout(this.touchTimeout),this.touchTimeout=setTimeout((()=>{this.isHover=!1}),1e4),this.distance>100?this.leftClick():this.distance<-100&&this.rightClick(),this.isTouchStart=!1,this.startX=0,this.distance=0,this.touchTimeout=null}}const testimonialSafetyDesign3_unqreplaceid_=new TestimonialSafetyDesign3_unqreplaceid_;';
        var profilesliderappendnode5 = `<div class="slide edw-slider-item" data-value="0"><div class="card"><img class="card-image" src="${Vvveb.serverurl}/CDN/teamdesign10/images/image1.png" alt=""><div class="card-content"><h2>Jacob Anderson</h2><p>Fire & Life Safety Coordinator</p></div></div></div>`;
        Vvveb.Components.extend("_base", "html/profileslider5", {
            name: "Profile slider 5",
            attributes: ['data-ebpb-profileslider5'],
            image: "icons/profileslider5.svg",
            classes: ['edwiser-pb-profileslider5'],
            html: (() => {
                return `<div class="edwiser-pb-profileslider5" data-vvveb-disabled-area contenteditable="false">${profilesliderhtml5}<style>${profileslidercss5}</style><script>${profilesliderjs5}</script></div>`;
            })(),
            beforeInit: function (node) {
                properties = [];
                var i = 0;
                var slideno = 0;
                var id = generateUniqueID();
                node.innerHTML = node.innerHTML.replaceAll("_unqreplaceid_", id);
                $(node).find(".edw-slider-item").each(function (e) {
                    i = generateUniqueID();
                    $(this).attr("data-value", slideno);
                    slideno++;
                    var regex = /edw-carousel-item-\d+/;
                    var matchedClass = "";
                    var hasMatchingClass = $(this).filter(function () {
                        var classNames = $(this).attr('class').split(' ');
                        return classNames.some(function (className) {
                            if (regex.test(className)) {
                                matchedClass = className;
                                return true;
                            }
                            return false;
                        });
                    }).length > 0;
                    $(this).removeClass(matchedClass);
                    $(this).addClass("edw-carousel-item-" + i);

                    properties.push(
                        {
                            name: "",
                            key: "slidergrouptitle" + slideno,
                            inputtype: EdwheaderInput,
                            edwclasses: "edwgroupheader",
                            data: {
                                header: "Profile " + slideno,
                                extraclass: "edwslideheading m-0",
                                type: "h6",
                                style: ""
                            }
                        },
                        {
                            name: "",
                            key: "deleteslideritem",
                            inputtype: EdwbuttonInput,
                            child: `.edw-carousel-item-${i}`,
                            edwclasses: "edwslidedelbtn",
                            data: { text: "", icon: "la-trash", extraclasses: "btn btn-outline-danger" },
                            onChange: function (node, value, input) {
                                $(node).remove();
                                Vvveb.Components.render("html/profileslider5");
                                return node;
                            },
                        },
                        {
                            name: SETTINGTITLES.NAME,
                            key: "sliderprofilename" + i,
                            htmlAttr: "innerHTML",
                            child: `.edw-carousel-item-${i} .card-content h2`,
                            inputtype: TextInput,
                            edwclasses: "edwinputfield",
                            onChange: function (node, value, input) {
                                if (value == "") {
                                    $(node).hide();
                                } else {
                                    $(node).show().text(value);
                                }
                                return node;
                            }
                        },
                        {
                            name: SETTINGTITLES.DESIGNATION,
                            key: "sliderprofiledesignation" + i,
                            htmlAttr: "innerHTML",
                            child: `.edw-carousel-item-${i} .card-content p`,
                            inputtype: TextInput,
                            edwclasses: "edwinputfield",
                            onChange: function (node, value, input) {
                                if (value == "") {
                                    $(node).hide();
                                } else {
                                    $(node).show().text(value);
                                }
                                return node;
                            }
                        },
                        {
                            name: SETTINGTITLES.IMAGE,
                            key: "sliderimage" + i,
                            htmlAttr: 'src',
                            inputtype: ImageInput,
                            edwclasses: "edwfilefield",
                            child: `.edw-carousel-item-${i} .card-image`,
                        }
                    );
                });

                properties = removeDeleteButton(node, properties, 1);
                removeSettingsOnSingleSlide(node);
                if (!$(node).find('.edw-slider-inner-container').children('.edw-slider-item').hasClass('active')) {
                    $(node).find('.edw-slider-item').first().addClass('active');
                }
                Indicatordesign2(node, i);

                //remove all option properties (but preserve static section properties)
                this.properties = this.properties.filter(function (item) {
                    // Preserve these static properties
                    if (item.key === "slidersectiontitle" || item.key === "slidersectiondescription") {
                        return true;
                    }
                    return item.key.indexOf("slider") === -1;
                });
                this.properties = properties.concat(this.properties);
                this.properties = disableaddnewslidebutton(node,this.properties, 10);
                slideIntervalfielddisabler(node);
                return node;
            },
            properties: [
                {
                    name: "",
                    key: "tabswarning",
                    inputtype: EdwheaderInput,
                    edwclasses: "edwgroupheader",
                    data: {
                        header: "Only 10 profiles are allowed",
                        extraclass: "edwslideheading m-0 p-3 border-0 alert alert-warning",
                        type: "h6",
                        style: ""
                    }
                },
                {
                    name: "",
                    key: "addNewSlide",
                    inputtype: EdwbuttonInput,
                    edwclasses: "edwnewslidebtn",
                    data: { text: "Add new slide", icon: "la-plus", extraclasses: "btn btn-outline-primary" },
                    onChange: function (node) {
                        //render component properties again to include the new column inputs
                        $(node).parent().find('.edw-slider-inner-container').append(profilesliderappendnode5);
                        Vvveb.Components.render("html/profileslider5");
                        return node;
                    }
                },
                {
                    name: SETTINGTITLES.TITLE,
                    key: "slidersectiontitle",
                    htmlAttr: "innerHTML",
                    child: `.right-content .title`,
                    inputtype: TextInput,
                    edwclasses: "edwinputfield",
                    onChange: function (node, value, input) {
                        if (value == "") {
                            $(node).hide();
                            $(node).closest(".section-container").find(".top-content-mobile .title").hide();
                        } else {
                            $(node).show().text(value);
                            $(node).closest(".section-container").find(".top-content-mobile .title").show().text(value);
                        }
                        return node;
                    }
                },
                {
                    name: SETTINGTITLES.DESCRIPTION,
                    key: "slidersectiondescription",
                    htmlAttr: "innerHTML",
                    child: `.right-content .desc`,
                    inputtype: TextInput,
                    edwclasses: "edwinputfield",
                    onChange: function (node, value, input) {
                        if (value == "") {
                            $(node).hide();
                            $(node).closest(".section-container").find(".top-content-mobile .desc").hide();
                        } else {
                            $(node).show().text(value);
                            $(node).closest(".section-container").find(".top-content-mobile .desc").show().text(value);
                        }
                        return node;
                    }
                },
                {
                    name: SETTINGTITLES.TITLECOLOR,
                    key: "color",
                    inline: true,
                    htmlAttr: "style",
                    col: 12,
                    inputtype: ColorInput,
                    edwclasses: "edwcolorfield",
                    child: '.right-content .title',
                    onChange: function (node, value, input) {
                        $(node).css('color', value);
                        $(node).closest(".section-container").find(".top-content-mobile .title").css('color', value);
                        return node;
                    }
                },
                {
                    name: SETTINGTITLES.DESCRIPTIONCOLOR,
                    key: "color",
                    inline: true,
                    htmlAttr: "style",
                    col: 12,
                    inputtype: ColorInput,
                    edwclasses: "edwcolorfield",
                    child: '.right-content .desc',
                    onChange: function (node, value, input) {
                        $(node).css('color', value);
                        $(node).closest(".section-container").find(".top-content-mobile .desc").css('color', value);
                        return node;
                    }
                },
                {
                    name: SETTINGTITLES.BGCOLOR,
                    key: "background-color",
                    inline: true,
                    htmlAttr: "style",
                    col: 12,
                    inputtype: ColorInput,
                    edwclasses: "edwcolorfield",
                    child: '.background',
                    onChange: function (node, value, input) {
                        $(node).css('background-color', value);
                        return node;
                    }
                },
                {
                    name: SETTINGTITLES.BACKGROUNDIMAGEPATTERN,
                    key: "backgroundimagepattern",
                    htmlAttr: 'src',
                    inputtype: ImageInput,
                    edwclasses: "edwfilefield",
                    child: '.background img',
                },
                {
                    name: SETTINGTITLES.SHOWNAVIGATIONBUTTONS,
                    key: "navigationbutton",
                    htmlAttr: "checked",
                    col: 12,
                    inline: true,
                    inputtype: CheckboxInput,
                    edwclasses: "edwcheckfield",
                    child: '.edw-slider-navigationbutton',
                    onChange: function (node, value, input) {
                        if (value == true) {
                            $(node).parent().parent().find('.slide-navigation .edw-control-prev').removeClass('d-none');
                            $(node).parent().parent().find('.slide-navigation .edw-control-next').removeClass('d-none');
                        } else {
                            $(node).parent().parent().find('.slide-navigation .edw-control-prev').addClass('d-none');
                            $(node).parent().parent().find('.slide-navigation .edw-control-next').addClass('d-none');
                        }
                        // var tempnode = $(node).closest('.edw-carousel')
                        // slidearrowcolorsettingstatushandler(tempnode,arrow=false, arrowandbullet = true, title = SETTINGTITLES.ARROWASSETCOLORINFOWITHBULLETS);
                        return node;
                    }
                },
                {
                    name: SETTINGTITLES.SHOWNAVIGATIONBULLETS,
                    key: "navigationbullets",
                    htmlAttr: "checked",
                    col: 12,
                    inline: true,
                    inputtype: CheckboxInput,
                    edwclasses: "edwcheckfield",
                    child: '.edw-slider-navigationbullets',
                    onChange: function (node, value, input) {
                        if (value == true) {
                            $(node).parent().parent().find('.slide-navigation .edw-carousel-indicators').removeClass('d-none');
                        } else {
                            $(node).parent().parent().find('.slide-navigation .edw-carousel-indicators').addClass('d-none');
                        }
                        var tempnode = $(node).closest('.edw-carousel')
                        // slidearrowcolorsettingstatushandler(tempnode,arrow=false, arrowandbullet = true, title = SETTINGTITLES.ARROWASSETCOLORINFOWITHBULLETS);
                        return node;
                    }
                },
                {
                    name: SETTINGTITLES.AUTOPLAYSLIDES,
                    key: "autoplayslides",
                    htmlAttr: "checked",
                    col: 12,
                    inline: true,
                    inputtype: CheckboxInput,
                    edwclasses: "edwcheckfield",
                    child: '.edw-slider-autoplay',
                    onChange: function (node, value, input) {
                        var slideintervalfield = $(input).closest('section').find('[data-key="slideinterval"]');
                        if (value == true) {
                            $(node).parent().find('.edw-carousel').attr('data-ride', 'carousel');
                            $(node).parent().find('.edw-carousel').attr('data-interval', '3000');
                            $(node).parent().find('.edw-carousel').attr('data-bs-ride', 'carousel');
                            $(node).parent().find('.edw-carousel').attr('data-bs-interval', '3000');
                            slideintervalfield.find('input[name="slideinterval"]').val('3000');
                        } else {
                            $(node).parent().find('.edw-carousel').removeAttr('data-ride');
                            $(node).parent().find('.edw-carousel').attr('data-interval', '0');
                            $(node).parent().find('.edw-carousel').removeAttr('data-bs-ride');
                            $(node).parent().find('.edw-carousel').attr('data-bs-interval', '0');
                            slideintervalfield.find('input[name="slideinterval"]').val('0');
                        }
                        slideIntervalfielddisabler(node);
                        return node;
                    }
                },
                {
                    name: SETTINGTITLES.PAUSESLIDESONHOVER,
                    key: "pauseslides",
                    htmlAttr: "checked",
                    col: 12,
                    inline: true,
                    inputtype: CheckboxInput,
                    edwclasses: "edwcheckfield",
                    child: '.edw-slider-pauseonhover',
                    onChange: function (node, value, input) {
                        if (value == true) {
                            $(node).parent().find('.edw-carousel').attr('data-pause', 'hover');
                            $(node).parent().find('.edw-carousel').attr('data-bs-pause', 'hover');
                        } else {
                            $(node).parent().find('.edw-carousel').attr('data-pause', 'false');
                            $(node).parent().find('.edw-carousel').attr('data-bs-pause', 'false');
                        }
                        return node;
                    }
                },
                {
                    name: SETTINGTITLES.SLIDEINTERVAL,
                    key: "slideinterval",
                    htmlAttr: "data-interval",
                    inputtype: TextInput,
                    edwclasses: "edwinputfield",
                    child: `.carousel`,
                    onChange: function (node, value, input) {

                        $(node).attr('data-interval', value);
                        $(node).attr('data-bs-interval', value);

                        return node;
                    }
                }
            ]
        });
    }
    function profileDesignIndicatorHandlder(node, i) {
        $(node).find(".edw-carousel-indicators").empty();
        var id = $(node).find('.edw-carousel').attr('id');
        var x = 1;
        $(node).find(".edw-slider-item").each(function (e) {
            $(node).find(".edw-carousel-indicators").append(`<li data-value="${$(this).attr("data-value")}" data-slide-to="${x}"></li>`);
            if ($(this).hasClass('active')) {
                $(node).find(`.edw-carousel-indicators li[data-slide-to='${x}']`).addClass('active');
            }
            x++;
        });
    }
    function removeDuplicateIndicators(node, i) {
        $(node).find(".edw-carousel-indicators").empty();
        var id = $(node).find('.edw-carousel').attr('id');
        var x = 0;
        $(node).find(".edw-slider-item").each(function (e) {
            $(node).find(".carousel-indicators").append(`<li data-target="#${id}" data-slide-to="${x}" data-bs-target="#${id}" data-bs-slide-to="${x}"></li>`);
            if ($(this).hasClass('active')) {
                $(node).find(`.edw-carousel-indicators li[data-slide-to='${x}']`).addClass('active');
            }
            x++;
        });
    }
    function Indicatordesign2(node, i) {
        $(node).find(".edw-carousel-indicators").empty();
        var id = $(node).find('.edw-carousel').attr('id');
        var x = 0;
        $(node).find(".edw-slider-item").each(function (e) {
            $(node).find(".edw-carousel-indicators").append(` <span class="indicator-btn" data-value="${$(this).attr('data-value')}"></span>`);
            if ($(this).hasClass('active')) {
                $(node).find(`.edw-carousel-indicators span[data-value="${$(this).attr('data-value')}"]`).addClass('active');
            }
            x++;
        });
    }
    function generatesinglecarouselfooteritem(node, carouselitem, i) {
        $profileimgurl = $(carouselitem).find('.edw-profile-img').attr('src');
        if ($(node).parent().find(`.edw-carousel .carousel-footer .footer-image-box div[data-value= "${i}"]`).length == 0) {
            $(node).parent().find('.edw-carousel .carousel-footer .footer-image-box').append(
                `<div class="profile-image-box " data-value="${i}">
                    <div>
                        <div>
                            <img src="${$profileimgurl}"
                                alt="profile-1" />
                        </div>
                    </div>
                </div>`
            );
        }
    }
    function deletecarouselfooterboxitem(node) {
        var data = $(node).attr('data-value');
        if ($(node).closest('.edw-carousel').find(`.carousel-footer .footer-image-box div[data-value= "${data}"]`).length > 0) {
            $(node).closest('.edw-carousel').find(`.carousel-footer .footer-image-box div[data-value= "${data}"]`).remove();
            console.log("footer box deleted sucessfully");
        }
    }
    function applyTextColorsBeforeInit(node, sliderbgclass) {
        var value = $(node).parent().find(`${sliderbgclass}:first-child`).css('background-color');
        var slideusernamecolor = $(node).parent().find('.testimonial-user-name:first-child').css('color');
        var slideuserdesgcolor = $(node).parent().find('.testimonial-user-desg:last-child').css('color');
        var slideuserdesccolor = $(node).parent().find('.edw-carousel-content-para:first-child').css('color');
        if (slideuserdesccolor == undefined) {
            slideuserdesccolor = $(node).parent().find('.edw-carousel-content-para:last-child').css('color');
        }
        $(node).parent().find(`${sliderbgclass}`).css('background-color', value);
        $(node).parent().find('.testimonial-user-name').css('color', slideusernamecolor);
        $(node).parent().find('.testimonial-user-desg').css('color', slideuserdesgcolor);
        $(node).parent().find('.edw-carousel-content-para').css('color', slideuserdesccolor);
    }
    function generateUniqueID() {
        // Get the current timestamp (milliseconds since Unix Epoch)
        var timestamp = new Date().getTime();

        // Generate a random number (0-99999)
        var randomNum = Math.floor(Math.random() * 100);

        // Concatenate the timestamp and random number to create the unique ID
        var uniqueID = timestamp + randomNum;

        return uniqueID;
    }
    function removeDeleteButton(node, properties, i) {
        var numberOfChildren = $(node).find(".edw-slider-item").length;
        if (numberOfChildren <= i) {
            properties = properties.map(function (item) {
                if (item.key == 'deleteslideritem') {
                    item.data['extraclasses'] = item.data['extraclasses'] + ' disabled';
                }
                return item;
            });
        }
        return properties;
    }


    function hideNavigatorsOnSingleSlide(node) {
        var numberOfChildren = $(node).find(".edw-slider-item").length;
        if (numberOfChildren == 1) {
                $(node).parent().find('.edw-control-prev').addClass('d-none');
                $(node).parent().find('.edw-control-next').addClass('d-none');
                $(node).parent().find('.edw-carousel-indicators').addClass('d-none');
        } else {
                $(node).parent().find('.edw-control-prev').removeClass('d-none');
                $(node).parent().find('.edw-control-next').removeClass('d-none');
                $(node).parent().find('.edw-carousel-indicators').removeClass('d-none');
        }
    }
    function slideIntervalfielddisabler(node) {
        $(document).ready(function () {
            // var autoplaycheckedstatus = $(node).parent().find('.edw-slider-autoplay').attr('checked');
            var autoplaycheckedstatus = $(document).find('[name="autoplayslides"]').is(":checked");
            console.log(autoplaycheckedstatus);
            if (!autoplaycheckedstatus) {
                $(document).find('#left-panel .edwinputfield[data-key="slideinterval"] input').attr('disabled', 'disabled');
            } else {
                $(document).find('#left-panel .edwinputfield[data-key="slideinterval"] input').removeAttr('disabled');
            }
        });
    }
    function removeSettingsOnSingleSlide(node) {
        var numberOfChildren = $(node).find(".edw-slider-item").length;
        var navbuttonstatus = $(node).find('.edw-slider-navigationbutton').attr('checked');
        var navbulletstatus = $(node).find('.edw-slider-navigationbullets').attr('checked');
        if (numberOfChildren == 1) {
            if (navbuttonstatus == 'checked') {
                $(node).parent().find('.edw-control-prev').addClass('d-none');
                $(node).parent().find('.edw-control-next').addClass('d-none');
                // $(node).find('.edw-slider-navigationbutton').removeAttr('checked');
            }
            if (navbulletstatus == 'checked') {
                $(node).parent().find('.edw-carousel-indicators').addClass('d-none');
                // $(node).find('.edw-slider-navigationbullets').removeAttr('checked');
            }

        } else {
            if (navbuttonstatus == 'checked') {
                $(node).parent().find('.edw-control-prev').removeClass('d-none');
                $(node).parent().find('.edw-control-next').removeClass('d-none');
                $(node).find('.edw-slider-navigationbutton').attr('checked', 'checked');
            }
            if (navbulletstatus == 'checked') {
                $(node).parent().find('.edw-carousel-indicators').removeClass('d-none');
                $(node).find('.edw-slider-navigationbullets').attr('checked', 'checked');
            }
        }
    }
    function disableaddnewslidebutton(node, properties, limit) {
        var numberOfChildren = $(node).find(".edw-slider-item").length;
        if (numberOfChildren == limit) {
            properties = properties.map(function (item) {
                if (item.key == 'addNewSlide') {
                    item.data['extraclasses'] = item.data['extraclasses'] + ' disabled';
                }
                return item;
            });

            properties = properties.map(function (item) {
                if (item.key == 'tabswarning') {
                    item.edwclasses  = item.edwclasses + ' d-none';

                    var $tempElement = $('<div>').addClass(item.edwclasses);
                    // Remove the 'disabled' class using jQuery
                    $tempElement.removeClass('d-none');

                    // Get the modified string without 'disabled' class
                    item.edwclasses = $tempElement.attr('class');
                }
                return item;
            });
        }else{
            properties = properties.map(function (item) {
                if (item.key == 'addNewSlide') {
                    item.data['extraclasses'] = item.data['extraclasses'] + ' disabled';

                    var $tempElement = $('<div>').addClass(item.data['extraclasses']);
                    // Remove the 'disabled' class using jQuery
                    $tempElement.removeClass('disabled');

                    // Get the modified string without 'disabled' class
                    item.data['extraclasses'] = $tempElement.attr('class');
                }
                return item;
            });

            properties = properties.map(function (item) {
                if (item.key == 'tabswarning') {
                    item.edwclasses = item.edwclasses + ' d-none';
                }
                return item;
            });
        }
        return properties;
    }
    return {
        init: function () {
            var blocks = ["html/profileslider1", "html/profileslider2", "html/profileslider3", "html/slider16", "html/profileslider5"];
            addBlocks(blocks);
        }
    }

});
