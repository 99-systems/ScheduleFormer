@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-10 col-lg-8">
                <div class="card mt-3">
                    <div class="card-header">
                        <span>Важно!</span>
                    </div>
                    <div class="card-body">
                    <span>
                        Этот сайт только для просмотра уроков, учителей, и составить примерно возможное расписание.
                    </span>
                    </div>
                </div>
                <div class="ajaxMsjDiv"><span id="ajaxLoading" class="ajaxMsj"
                                              style="background-color: rgb(249, 253, 255); color: rgb(0, 128, 192); font-weight: bold; display: none;"></span>&nbsp;
                </div>

                <style>
                    .clsTbl {
                        width: 100%;
                        /*font-family: arial, sans-serif;*/
                        border-collapse: collapse;
                        margin: 0px;
                        border-color: #0000BB;
                        background-color: white;
                    }

                    .clsTd {
                        border: 1px solid #ddd;
                        padding: 6px 4px;
                        margin: 0px;
                        font-size: 12px;
                        vertical-align: middle;
                    }

                    a.button {
                        color: #6e6e6e;
                        font: bold 12px Helvetica, Arial, sans-serif;
                        text-decoration: none;
                        padding: 7px 12px;
                        position: relative;
                        display: inline-block;
                        text-shadow: 0 1px 0 #fff;
                        -webkit-transition: border-color .218s;
                        -moz-transition: border .218s;
                        -o-transition: border-color .218s;
                        transition: border-color .218s;
                        background: #f3f3f3;
                        background: -webkit-gradient(linear, 0% 40%, 0% 70%, from(#F5F5F5), to(#F1F1F1));
                        background: -moz-linear-gradient(linear, 0% 40%, 0% 70%, from(#F5F5F5), to(#F1F1F1));
                        border: solid 1px #dcdcdc;
                        border-radius: 2px;
                        -webkit-border-radius: 2px;
                        -moz-border-radius: 2px;
                        margin-right: 10px;
                    }

                    .selectable {
                        background-color: lightblue;
                        color: black;
                        cursor: pointer;
                    }

                    .unavailable {
                        background-color: #ffaaaa;
                        color: grey;
                        cursor: not-allowed;
                    }

                    .important {
                        color: red;
                        font-weight: bold;
                    }

                    .hideDiv {
                        height: 0px;
                        opacity: 0;
                        overflow: hidden;
                        transition: width 1s;
                    }

                    .hideshow {
                        height: 100%;
                        opacity: 1;
                        overflow: hidden;
                        animation-name: forhideshow;
                        animation-duration: 0.1s;
                    }

                    .showDiv {
                        height: auto;
                        opacity: 1;
                        overflow: hidden;
                        transition: width 1s;
                    }

                    .selected {
                        background-color: #87b0eb;
                        color: black;
                        cursor: pointer;
                    }
                </style>

                {{--            {{$cookies}}--}}

                <div style="display:flex; justify-content: center; margin-bottom: 20px">
                    <a href="#" class="button" id="showProgg" style="display: none;"
                       onclick="swapShowProg(); return false;">Показать программу/уроки</a>
                </div>

                {!! $htmlContent !!}

                <div id="divCourseSearchType"></div>
                {{--            <div class="background-text" style="position: fixed;--}}
                {{--            top: 50%;--}}
                {{--            left: 50%;--}}
                {{--            transform: translate(-50%, -50%);--}}
                {{--            font-size: 5rem;--}}
                {{--            color: rgba(0, 0, 0, 0.06);--}}
                {{--            white-space: nowrap;--}}
                {{--            z-index: 999;--}}
                {{--            user-select: none;--}}
                {{--            pointer-events: none;">--}}
                {{--                https://t.me/quota_check--}}
                {{--            </div>--}}
                <div id="schedulee">
                    <br>

                    <div style="display:flex; justify-content: center; ">
                        <div id="tableContainer" style="display: none;">
                            <table class="clTbl" id="sectionsTable" width="100%" border="1">
                                <!-- Таблица будет заполняться динамически -->
                            </table>
                        </div>
                    </div>
                    <div style="display:flex; justify-content: center; ">

                        <a href="#" class="button" style="margin: 5px" onclick="clearSched(); return false;">Очистить
                            всё</a>
                    </div>
                    <a href="https://t.me/quota_check" class="channel-link" target="_blank">Перейти в канал, там много
                        интереснго и полезного</a>

                    <table class="clTbl" width="100%">
                        <tbody>
                        <tr>
                            <td align="center" class="ctg" height="30"
                                style="font-size:12px; font-weight:bold; background-color: #DDF4FF;" width="60">Day/Hour
                            </td>
                            <td align="center" class="ctg" style="background-color: #DDF4FF" width="15.384615384615%">
                                <span style="font-size:14px; font-weight:bold; color:black;" title="Monday">Mo</span>
                            </td>
                            <td align="center" class="ctg" style="background-color: #DDF4FF" width="15.384615384615%">
                                <span style="font-size:14px; font-weight:bold; color:black;" title="Tuesday">Tu</span>
                            </td>
                            <td align="center" class="ctg" style="background-color: #DDF4FF" width="15.384615384615%">
                                <span style="font-size:14px; font-weight:bold; color:black;" title="Wednesday">We</span>
                            </td>
                            <td align="center" class="ctg" style="background-color: #DDF4FF" width="15.384615384615%">
                                <span style="font-size:14px; font-weight:bold; color:black;" title="Thursday">Th</span>
                            </td>
                            <td align="center" class="ctg" style="background-color: #DDF4FF" width="15.384615384615%">
                                <span style="font-size:14px; font-weight:bold; color:black;" title="Friday">Fr</span>
                            </td>
                            <td align="center" class="ctg" style="background-color: #DDF4FF" width="15.384615384615%">
                                <span style="font-size:14px; font-weight:bold; color:black;" title="Saturday">Sa</span>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" class="ctg" height="50" style="background-color: #DDF4FF"><span
                                    style="font-weight:bold">08:30</span><br/><span style="color:#999">09:20</span></td>
                            <td align="center" class="ctg" id="s1.08:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s2.08:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s3.08:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s4.08:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s5.08:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s6.08:30" style="font-size: 10px; padding: 0;"></td>
                        </tr>
                        <tr>
                            <td align="center" class="ctg" height="50" style="background-color: #DDF4FF"><span
                                    style="font-weight:bold">09:30</span><br/><span style="color:#999">10:20</span></td>
                            <td align="center" class="ctg" id="s1.09:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s2.09:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s3.09:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s4.09:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s5.09:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s6.09:30" style="font-size: 10px; padding: 0;"></td>
                        </tr>
                        <tr>
                            <td align="center" class="ctg" height="50" style="background-color: #DDF4FF"><span
                                    style="font-weight:bold">10:30</span><br/><span style="color:#999">11:20</span></td>
                            <td align="center" class="ctg" id="s1.10:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s2.10:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s3.10:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s4.10:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s5.10:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s6.10:30" style="font-size: 10px; padding: 0;"></td>
                        </tr>
                        <tr>
                            <td align="center" class="ctg" height="50" style="background-color: #DDF4FF"><span
                                    style="font-weight:bold">11:30</span><br/><span style="color:#999">12:20</span></td>
                            <td align="center" class="ctg" id="s1.11:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s2.11:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s3.11:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s4.11:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s5.11:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s6.11:30" style="font-size: 10px; padding: 0;"></td>
                        </tr>
                        <tr>
                            <td align="center" class="ctg" height="50" style="background-color: #DDF4FF"><span
                                    style="font-weight:bold">12:30</span><br/><span style="color:#999">13:20</span></td>
                            <td align="center" class="ctg" id="s1.12:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s2.12:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s3.12:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s4.12:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s5.12:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s6.12:30" style="font-size: 10px; padding: 0;"></td>
                        </tr>
                        <tr>
                            <td align="center" class="ctg" height="50" style="background-color: #DDF4FF"><span
                                    style="font-weight:bold">13:30</span><br/><span style="color:#999">14:20</span></td>
                            <td align="center" class="ctg" id="s1.13:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s2.13:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s3.13:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s4.13:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s5.13:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s6.13:30" style="font-size: 10px; padding: 0;"></td>
                        </tr>
                        <tr>
                            <td align="center" class="ctg" height="50" style="background-color: #DDF4FF"><span
                                    style="font-weight:bold">14:30</span><br/><span style="color:#999">15:20</span></td>
                            <td align="center" class="ctg" id="s1.14:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s2.14:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s3.14:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s4.14:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s5.14:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s6.14:30" style="font-size: 10px; padding: 0;"></td>
                        </tr>
                        <tr>
                            <td align="center" class="ctg" height="50" style="background-color: #DDF4FF"><span
                                    style="font-weight:bold">15:30</span><br/><span style="color:#999">16:20</span></td>
                            <td align="center" class="ctg" id="s1.15:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s2.15:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s3.15:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s4.15:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s5.15:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s6.15:30" style="font-size: 10px; padding: 0;"></td>
                        </tr>
                        <tr>
                            <td align="center" class="ctg" height="50" style="background-color: #DDF4FF"><span
                                    style="font-weight:bold">16:30</span><br/><span style="color:#999">17:20</span></td>
                            <td align="center" class="ctg" id="s1.16:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s2.16:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s3.16:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s4.16:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s5.16:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s6.16:30" style="font-size: 10px; padding: 0;"></td>
                        </tr>
                        <tr>
                            <td align="center" class="ctg" height="50" style="background-color: #DDF4FF"><span
                                    style="font-weight:bold">17:30</span><br/><span style="color:#999">18:20</span></td>
                            <td align="center" class="ctg" id="s1.17:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s2.17:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s3.17:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s4.17:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s5.17:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s6.17:30" style="font-size: 10px; padding: 0;"></td>
                        </tr>
                        <tr>
                            <td align="center" class="ctg" height="50" style="background-color: #DDF4FF"><span
                                    style="font-weight:bold">18:30</span><br/><span style="color:#999">19:20</span></td>
                            <td align="center" class="ctg" id="s1.18:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s2.18:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s3.18:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s4.18:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s5.18:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s6.18:30" style="font-size: 10px; padding: 0;"></td>
                        </tr>
                        <tr>
                            <td align="center" class="ctg" height="50" style="background-color: #DDF4FF"><span
                                    style="font-weight:bold">19:30</span><br/><span style="color:#999">20:20</span></td>
                            <td align="center" class="ctg" id="s1.19:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s2.19:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s3.19:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s4.19:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s5.19:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s6.19:30" style="font-size: 10px; padding: 0;"></td>
                        </tr>
                        <tr>
                            <td align="center" class="ctg" height="50" style="background-color: #DDF4FF"><span
                                    style="font-weight:bold">20:30</span><br/><span style="color:#999">21:20</span></td>
                            <td align="center" class="ctg" id="s1.20:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s2.20:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s3.20:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s4.20:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s5.20:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s6.20:30" style="font-size: 10px; padding: 0;"></td>
                        </tr>
                        <tr>
                            <td align="center" class="ctg" height="50" style="background-color: #DDF4FF"><span
                                    style="font-weight:bold">21:30</span><br/><span style="color:#999">22:20</span></td>
                            <td align="center" class="ctg" id="s1.22:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s2.22:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s3.22:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s4.22:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s5.22:30" style="font-size: 10px; padding: 0;"></td>
                            <td align="center" class="ctg" id="s6.22:30" style="font-size: 10px; padding: 0;"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <style>.ctg {
                        border: 1px solid #FAD163;
                        padding: 2px;
                        margin: 0px;
                        vertical-align: center;
                    }
                </style>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        let sectionsInfo;
        let i;
        var showedSections = [];
        var selectedN = [];
        selectedN["SID"] = "";
        selectedN["AVAILALE"] = true;
        var selectedP = [];
        selectedP["SID"] = "";
        selectedP["AVAILALE"] = true;
        var selectedL = [];
        selectedL["SID"] = "";
        selectedL["AVAILALE"] = true;
        var inSchedule = [];
        inSchedule["N"] = [];
        inSchedule["P"] = [];
        inSchedule["L"] = [];
        var available = true;
        let uniqid = 0;
        let addedSections = []

        let showSems = true;
        let showingMufSQ = false;
        // function RemoveFromSchedule(type){
        //     if(type == "N"){
        //         for(i = 0; i < inSchedule["N"].length; i++){
        //             elem = document.getElementById(inSchedule["N"][i]);
        //             if(elem != null) elem.parentNode.removeChild(elem);
        //         }
        //     }
        //     if(type == "P" || type == "N"){
        //         for(i = 0; i < inSchedule["P"].length; i++){
        //             elem = document.getElementById(inSchedule["P"][i]);
        //             if(elem != null) elem.parentNode.removeChild(elem);
        //         }
        //     }
        //     if(type == "L" || type == "N"){
        //         for(i = 0; i < inSchedule["L"].length; i++){
        //             elem = document.getElementById(inSchedule["L"][i]);
        //             if(elem != null) elem.parentNode.removeChild(elem);
        //         }
        //     }
        // }

        $(document).ready(function () {

            $("div[style*='border:1px solid #eee']").hide();
            let modContent = document.querySelector(".modContent");
            let semstrs = modContent.querySelectorAll(".clsTbl");

            semstrs.forEach(sem => {
                let trs = sem.querySelectorAll("tr");

                for (let i = 1; i < trs.length; i++) {
                    let tds = trs[i].querySelectorAll("td");
                    let elective = false;
                    if (tds[10].innerText && tds[10].innerText.trim() !== 'In basket') continue;

                    let dersKod = tds[1].innerText.split("\n")[0].trim();

                    if (tds[2].querySelector('b') && tds[2].querySelector('b').title === 'Elective group') {
                        // console.log('below')
                        elective = true;
                    }

                    if (elective === false) {
                        tds[10].innerHTML = `<a class="button" id="idBtnShowSections_INF_313"
                            style="margin-right:0px" href="#"
                            onclick="SearchCourse('${dersKod}'); return false;">Choose</a>`;
                        continue;
                    }


                    let text = tds[2].innerText;
                    let codes = text.match(/\(([^)]+)\)$/)[1].split(/, |\/\s*/).map(code => {
                        // Убираем пробелы и форматируем код, если нужно
                        code = code.trim();
                        return code.length === 6 ? code.slice(0, 3) + " " + code.slice(3) : code;
                    });

                    tds[10].innerHTML = `<a class="button" id="idBtnShowSections_INF_313"
                        style="margin-right:0px" href="#"
                        onclick="listCourses('${codes}'); return false;">Choose</a>`;
                }
            })
        });

        async function listCourses(dersKods) {
            dersKods = dersKods.split(',')
            let lessns = [];
            let htmlcode = '';


            alert('Подождите пожалуйста...')

            for (let i = 0; i < dersKods.length; i++) {
                let lessn = await schc(dersKods[i]);  // Функция schc должна быть определена, если не была.
                // console.log(lessn)
                if (!lessn) {
                    alert('Something went wrong');
                    return;
                }

                if (lessn["CODE"] == 1) {
                    let parser = new DOMParser();
                    htmlcode = parser.parseFromString(lessn["DATA"], 'text/html').body;
                } else if (lessn["CODE"] < 0) {
                    alert(lessn["DATA"]);
                    return;
                }

                // Извлечение текста из элемента с классом 'desc' внутри htmlcode.
                let name = htmlcode.querySelector('div.desc') ? htmlcode.querySelector('div.desc').innerText : 'No description';

                // Добавляем объект в массив lessns
                lessns.push({
                    dk: dersKods[i],
                    name: name
                });
            }

            cb_ListCourses(lessns)

            // Возвращаем массив с результатами
            // return lessns;
        }

        function cb_ListCourses(lessns) {

            let content = `<div style="border:1px solid #eee; padding:2px">
			     <div class="moddesc">You need to select proper course for this elective group.</div>
				<table class="clsTbl">
					<tbody><tr><td colspan="8"></td>
                    </tr>
					<tr>
						<td width="15" align="center" class="clsTd" style="color:#999999">№</td>
						<td align="center" class="clsTd" style="color:#999999">Course code</td>
						<td align="left" class="clsTd" style="color:#999999">Name</td>
						<td width="40" align="center" class="clsTd"></td>
					</tr>
						`
            for (let i = 0; i < lessns.length; i++) {
                content += `<tr>
							<td align="center" width="20" class="clsTd" style="color:#999999">${i + 1}</td>
							<td align="center" width="75" class="clsTd">${lessns[i].dk}</td>
							<td class="clsTd">${lessns[i].name}</td>
							<td align="center" class="clsTd">
							<a class="button" id="idBtnShowSections_INF_313"
                style="margin-right:0px" href="#"
                onclick="SearchCourse('${lessns[i].dk}'); return false;">Choose</a>
</td>
						</tr>`
            }
            content += `<tr><td colspan="9">&nbsp;</td></tr>
                    <tr><td colspan="9">&nbsp;</td></tr>
               </tbody></table></div>`

            var cnt = document.getElementById("divCourseSearchType");
            cnt.style.display = 'block';
            // HideAjaxLoadImage();

            cnt.innerHTML = content;
            if (showSems) {
                swapShowProg();
            } else {
                showingMufSQ = false;
            }
            untoavaible()
        }


        async function schc(dersKod) {
            return await $.ajax({
                url: '/SearchCourse',
                method: 'POST',
                data: {dk: dersKod},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    // console.log(response)
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(textStatus)
                    console.error('Ошибка запроса:', textStatus, errorThrown);
                }
            });
        }

        function SearchCourse(dersKod) {
            $.ajax({
                url: '/SearchCourse',
                method: 'POST',
                data: {dk: dersKod},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    cb_SearchCourse(response);
                    // console.log(response)
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(textStatus)
                    console.error('Ошибка запроса:', textStatus, errorThrown);
                }
            });
        }

        function cb_SearchCourse(res) {
            var cnt = document.getElementById("divCourseSearchType");
            cnt.style.display = 'block';
            // HideAjaxLoadImage();

            if (res["CODE"] == 1) {
                cnt.innerHTML = res["DATA"];
                if (showSems) {
                    swapShowProg();
                } else {
                    showingMufSQ = false;
                }
                untoavaible()
                if (res["DATA2"] != undefined) sectionsInfo = res["DATA2"];
                // RemoveFromSchedule("N");
            } else if (res["CODE"] < 0) {
                alert(res["DATA"]);
            }
        }

        function ShowAvailableAllSections(prms) {
            var data = {
                dk: prms.dersKod,
                pc: prms.progCode,
                py: prms.progYear,
                progTrack: prms.progTrack
            };

            if (prms.sentFrom == 'ProgramByElective') {
                data.muf_sq_id = prms.muf_sq_id;
            }


            $.ajax({
                url: '/ShowAvailableAllSections',
                method: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    cb_ShowAvailableAllSections(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error('Ошибка запроса:', textStatus, errorThrown);
                }
            });
        }

        function cb_ShowAvailableAllSections(res) {
            var cnt = document.getElementById("divCourseSearchType");
            cnt.style.display = 'block';
            // HideAjaxLoadImage();

            if (res["CODE"] == 1) {
                cnt.innerHTML = res["DATA"];
                if (showSems) {
                    swapShowProg();
                } else {
                    showingMufSQ = false;
                }
                untoavaible()
                if (res["DATA2"] != undefined) sectionsInfo = res["DATA2"];
                // RemoveFromSchedule("N");
            } else if (res["CODE"] < 0) {
                alert(res["DATA"]);
            }
        }

        function swapShowProg() {
            var showBtn = document.getElementById("showProgg");
            var modContent = document.querySelector(".modContent");
            if (showBtn.style.display === 'none') {
                showBtn.style.display = 'block'
                showSems = false;
            } else {
                showBtn.style.display = 'none'
                showSems = true;
            }
            modContent.style.display = modContent.style.display === 'none' ? 'block' : 'none';
        }

        function selectSectionDiv(sid, showID = null) {
            let plDiv = document.getElementById("plDiv");
            if (plDiv.className == "hideDiv") {
                plDiv.className = "showDiv";
            } else {
                plDiv.classList.remove("hideshow");
                void plDiv.offsetHeight;
                plDiv.classList.add("hideshow");
            }
            var inputElement = document.getElementById("rbs" + sid);
            if (inputElement != undefined && inputElement.disabled != true) inputElement.checked = true;
            if (showID == null || showID == 1) selectSection(sid);
            else if (showID == 0) {
                SelectNormalSection(sid);
                document.getElementById("consentdiv").style.display = "none";
                document.getElementById("plTable").style.display = "none";
                msjDiv = document.getElementById("CRMsj");
                msjDiv.innerHTML = '<div class="warning"><div>Consent Request NOT AVAILABLE for this section..!</div></div>';
                msjDiv.style.display = "";
            } else if (showID == 2) {
                SelectNormalSection(sid);
            } else if (showID == 3) {
                SelectNormalSection(sid);
                document.getElementById("consentdiv").style.display = "none";
                document.getElementById("plTable").style.display = "none";
                msjDiv = document.getElementById("CRMsj");
                msjDiv.innerHTML = '<div class="info"><div>You have already sent request</div></div>';
                msjDiv.style.display = "";
            } else if (showID == 4) {
                SelectNormalSection(sid);
                document.getElementById("consentdiv").style.display = "none";
                document.getElementById("plTable").style.display = "none";
                msjDiv = document.getElementById("CRMsj");
                msjDiv.innerHTML = '<div class="warning"><div>You were refused</div></div>';
                msjDiv.style.display = "";
            }
        }

        function selectSection(sid) {

            plTable = document.getElementById("plTable");
            document.getElementById("consentdiv").style.display = "none";
            document.getElementById("CRMsj").style.display = "none";

            var sectionNorm = sectionsInfo[0]; // array of normal  sections
            var sectionPrac = sectionsInfo[1]; //          pratice
            var sectionLab = sectionsInfo[2];  //          lab

            if (sid in sectionNorm) {
                SelectNormalSection(sid);
                //Show practice and lab sections nad hide previous sections 'display = none'
                prac = (sectionNorm[sid]["PRACTICE"] != null && sectionNorm[sid]["PRACTICE"].trim() != "") ? sectionNorm[sid]["PRACTICE"].split(",") : [];
                lab = (sectionNorm[sid]["LAB"] != null && sectionNorm[sid]["LAB"].trim() != "") ? sectionNorm[sid]["LAB"].split(",") : [];
                pracLab = prac.concat(lab);
                // console.log(pracLab)
                if (pracLab.length != 0) {
                    for (i = 0; i < pracLab.length; i++) {
                        elem = document.getElementById("pl" + pracLab[i]);
                        if (elem != null) {
                            elem.style.display = "";
                            showedSections.push(pracLab[i]);
                        }
                    }
                    plTable.style.display = ""
                } else {
                    plTable.style.display = "none";
                }

                //Refresh practice and lab sections 'css animation'
                plDiv = document.getElementById("plDiv");
                if (plDiv.className == "hideDiv") {
                    plDiv.className = "showDiv";
                } else {
                    plDiv.classList.remove("hideshow");
                    void plDiv.offsetHeight;
                    plDiv.classList.add("hideshow");
                }
            } else if (sid in sectionPrac) {
                selectedElem = document.getElementById("pl" + sid);
                if (selectedElem != null) {
                    if (selectedP["SID"] != "") {
                        console.log("Remove old one");
                        oldSelElem = document.getElementById("pl" + selectedP["SID"]);
                        if (oldSelElem != null) oldSelElem.className = "sections selectable";
                    }
                    selectedElem.className = "sections selected";
                    RemoveFromSchedule("P");
                    selectedP["SID"] = sid;
                    selectedP["AVAILALE"] = SetToSchedule(sid, sectionPrac);
                }
            } else if (sid in sectionLab) {
                selectedElem = document.getElementById("pl" + sid);
                if (selectedElem != null) {
                    if (selectedL["SID"] != "") {
                        oldSelElem = document.getElementById("pl" + selectedL["SID"]);
                        if (oldSelElem != null) oldSelElem.className = "sections selectable";
                    }
                    selectedElem.className = "sections selected";
                    RemoveFromSchedule("L");
                    selectedL["SID"] = sid;
                    selectedL["AVAILALE"] = SetToSchedule(sid, sectionLab);
                }
            }
        }

        function SelectNormalSection(sid) {

            //Hide previous practice and lab sections
            for (var i = 0; i < showedSections.length; i++) {
                elem = document.getElementById("pl" + showedSections[i]);
                if (elem != null) elem.style.display = "none";
            }
            showedSections = [];
            //Changing section style to "selected" and reset old one (normal,practice,lab sections)
            selectedElem = document.getElementById("norm" + sid);
            if (selectedElem != null) {
                // Resetting style previous selected normal section
                if (selectedN["SID"] != "") {
                    oldSelElem = document.getElementById("norm" + selectedN["SID"]);
                    if (oldSelElem != null) oldSelElem.className = "sections selectable";
                }

                // Setting style to selected normal section
                selectedElem.className = "sections selected";
                RemoveFromSchedule("N");
                selectedN["SID"] = sid;
                selectedN["AVAILALE"] = SetToSchedule(sid, sectionsInfo[0]);

                // Reset Practice sections 'uncheck'
                if (selectedP["SID"].trim() != "") {
                    oldSelElem = document.getElementById("pl" + selectedP["SID"]);
                    if (oldSelElem != null) oldSelElem.className = "sections selectable";
                    oldSelInput = document.getElementById("rbs" + selectedP["SID"]);
                    if (oldSelInput != null) oldSelInput.checked = false;
                }
                selectedP["SID"] = "";
                selectedP["AVAILALE"] = true;

                // Reset Lab sections 'uncheck'
                if (selectedL["SID"].trim() != "") {
                    oldSelElem = document.getElementById("pl" + selectedL["SID"]);
                    if (oldSelElem != null) oldSelElem.className = "sections selectable";
                    oldSelInput = document.getElementById("rbs" + selectedL["SID"]);
                    if (oldSelInput != null) oldSelInput.checked = false;
                }
                selectedL["SID"] = "";
                selectedL["AVAILALE"] = true;
            }
        }

        function SetToSchedule(sid, arr) {
            if (!(sid in arr) || !("SCHEDULE" in arr[sid])) return false;

            style = "selectable";
            available = true;
            if (arr[sid]["SCHEDULE"] == null || arr[sid]["SCHEDULE"] == "") {
                if (arr[sid]["K_TEOR"] == "0" || arr[sid]["COURSE_TYPE"] != "N") {
                    return available;
                } else {
                    available = false;
                    return available;
                }
            }
            sarr = arr[sid]["SCHEDULE"].split(",");
            for (i = 0; i < sarr.length; i++) {
                elem = document.getElementById("s" + sarr[i]);
                if (elem == null) continue;
                if (elem.innerHTML.trim() != "" && arr[sid]["GRADING_TYPE"] != "A") {
                    style = "unavailable";
                    available = false;
                }
                innerdiv = document.createTextNode(arr[sid]["DERS_KOD"] + ' [' + arr[sid]["SECTION"] + '-' + arr[sid]["TYPE"] + ']');
                div = document.createElement("div");
                id = "d" + sid + sarr[i];
                div.id = id;
                div.className = "sections " + style;
                div.appendChild(innerdiv);
                elem.appendChild(div);
                inSchedule[arr[sid]["TYPE"]].push(id);
            }
            return available;
        }


        function RemoveFromSchedule(type) {
            if (type == "N") {
                for (i = 0; i < inSchedule["N"].length; i++) {
                    elem = document.getElementById(inSchedule["N"][i]);
                    if (elem != null && elem.style.backgroundColor !== 'rgba(0, 153, 0, 0.5)') elem.parentNode.removeChild(elem);
                }
            }
            if (type == "P" || type == "N") {
                for (i = 0; i < inSchedule["P"].length; i++) {
                    elem = document.getElementById(inSchedule["P"][i]);
                    if (elem != null && elem.style.backgroundColor !== 'rgba(0, 153, 0, 0.5)') elem.parentNode.removeChild(elem);
                }
            }
            if (type == "L" || type == "N") {
                for (i = 0; i < inSchedule["L"].length; i++) {
                    elem = document.getElementById(inSchedule["L"][i]);
                    if (elem != null && elem.style.backgroundColor !== 'rgba(0, 153, 0, 0.5)') elem.parentNode.removeChild(elem);
                }
            }
        }

        function AddCourse(prms) {
            var secSQ = '';
            var secLab = '';
            var secNorm = '';
            var secPrac = '';
            // var trackID = document.getElementById("selectTrack").value;

            if (!selectedN["AVAILALE"] || !selectedP["AVAILALE"] || !selectedL["AVAILALE"]) {
                alert("Unavailable sections selected!");
                return;
            }
            if (prms.muf_sq_id > 0) {
                secSQ = prms.muf_sq_id;
            } else {
                var contSQ = document.getElementById("tblElectiveGroups");
                if (contSQ) {
                    var sq = indexfFindChilds(contSQ, [['type', 'radio'], ['name', "mufSQ[]"]]);
                    if (sq.length > 0) {
                        for (i = 0; i < sq.length; i++) if (sq[i].checked) secSQ = sq[i].value;
                        if (secSQ == '') {
                            alert('Group of elective course is not identified!');
                            return;
                        }
                    }
                }
            }

            dersKod = document.getElementById("derskod");
            if (dersKod == null) {
                alert("Something went wrong(Course Code doesn't exist)");
                return;
            }
            var contObj = document.getElementById("tblSections");
            var contPL = document.getElementById("plDiv");
            indexfFindChilds(contObj, [['type', 'radio'], ['checked', true], ['name', "sectionNorm[]"]], function (o) {
                secNorm = o.value;
            });
            if (secNorm == '') {
                alert('Normal section of course is not identified!');
                return;
            }

            var pracs = indexfFindChilds(contPL, [['type', 'radio'], ['name', "sectionPractise[]"]]);
            if (pracs.length > 0) {
                for (i = 0; i < pracs.length; i++) if (pracs[i].checked) secPrac = pracs[i].value;
                if (secPrac == '') {
                    alert('Practice section of subject is not identified.');
                    return;
                }
            }

            var labs = indexfFindChilds(contPL, [['type', 'radio'], ['name', "sectionLab[]"]]);
            if (labs.length > 0) {
                for (i = 0; i < labs.length; i++) if (labs[i].checked) secLab = labs[i].value;
                if (secLab == '') {
                    alert('LAB section of subject is not identified.');
                    return;
                }
            }


            sels = [selectedN, selectedP, selectedL]
            let showLessons = [{
                type: '',
                teacher: '',
                group: '',
                time: '',
            }, {
                type: '',
                teacher: '',
                group: '',
                time: '',
            }, {
                type: '',
                teacher: '',
                group: '',
                time: '',
            },]
            i = 0
            sels.forEach(sel => {
                if (!sel["SID"] || !sectionsInfo[i][sel["SID"]]["SCHEDULE"]) {
                    i++
                    return
                }


                let chosen = sectionsInfo[i][sel["SID"]]
                showLessons[i].type = chosen['TYPE']
                showLessons[i].teacher = chosen['TEACHER']
                showLessons[i].group = chosen['SECTION']
                showLessons[i].time = chosen['SCHEDULE']


                let checkk = chosen["SCHEDULE"].split(",")
                checkk.forEach(ccc => {
                    document.getElementById('d' + sel["SID"] + ccc).style.backgroundColor = 'rgba(0, 153, 0, 0.5)';
                    document.getElementById('d' + sel["SID"] + ccc).classList.add('un' + uniqid);
                });
                i++
            });

            console.log('sq-' + secSQ, '|L-' + secLab, '|N-' + secNorm, '|P-' + secPrac)
            console.log(dersKod.value, uniqid, showLessons)
            addedSections.push({
                dersKod: dersKod.value,
                uniqid: uniqid,
                info: showLessons
            })
            uniqid++;
            refreshTable()
        }


        function refreshTable() {
            // Получаем таблицу и её родителя
            const tableContainer = document.getElementById('tableContainer'); // Контейнер, где расположена таблица
            const table = document.getElementById('sectionsTable'); // Таблица для отображения данных

            // Очистка таблицы перед заполнением
            table.innerHTML = '';

            if (addedSections.length === 0) {
                // Если данных нет, скрываем таблицу
                tableContainer.style.display = 'none';
                return;
            }

            // Если данные есть, показываем таблицу
            tableContainer.style.display = 'block';

            // Добавляем заголовок таблицы
            const headerRow = document.createElement('tr');
            headerRow.innerHTML = `
        <th class="ctg">DersKod</th>
<!--        <th class="ctg">Type</th>-->
<!--        <th class="ctg">Group</th>-->
        <th class="ctg">Teacher</th>
        <th class="ctg">Actions</th>
    `;
            table.appendChild(headerRow);

            // Заполняем таблицу данными из addedSections


            addedSections.forEach(section => {
                let tchinf = ''
                section.info.forEach(inf => {
                    if (inf.type === '') return;

                    tchinf += inf.type + '|' + inf.group + ': ' + inf.teacher + '\n';
                })


                const row = document.createElement('tr');
                row.innerHTML = `
            <td class="ctg">${section.dersKod}</td>
<!--            <td>${section.info.group}</td>-->
            <td class="ctg"><span>
                   ${tchinf}
            </span></td>
            <td class="ctg">
                <a class="button" onclick="dropSection(${section.uniqid})">Delete</a>
            </td>
        `;
                table.appendChild(row);
            });
        }

        // Функция удаления секции
        function dropSection(uni) {
            // Удаляем элементы с классом uni
            document.querySelectorAll(`.un${uni}`).forEach(element => {
                element.remove();
            });

            // Удаляем секцию из addedSections
            const index = addedSections.findIndex(section => section.uniqid === uni);
            if (index !== -1) {
                addedSections.splice(index, 1);
            }

            // Обновляем таблицу
            refreshTable();
        }


        function indexfFindChilds(obj, conditionsArray, callbackFunc) {
            /* This script is written by Shamil Mehdiyev, supervisor@box.az */
            var temp = [];
            var node;
            var indexfQuote;
            if (obj.childNodes && obj.childNodes.length) {
                for (var i = 0; i < obj.childNodes.length; i++) {
                    var allpassed = true;
                    node = obj.childNodes[i];
                    for (var j = 0; j < conditionsArray.length; j++) { //openDOMBrowser(nodes[i])
                        indexfQuote = "'";
                        if (typeof (conditionsArray[j][1]) == 'boolean') indexfQuote = "";
                        if (!eval("node." + conditionsArray[j][0] + " == " + indexfQuote + conditionsArray[j][1] + indexfQuote)) {
                            allpassed = false;
                            break;
                        }
                    }
                    if (allpassed) {
                        ///demek sertler odendi
                        if (typeof callbackFunc == 'function') {
                            callbackFunc(node);
                        }
                        temp[temp.length] = node;
                    }
                    if (node.childNodes && node.childNodes.length) {
                        temp = temp.concat(indexfFindChilds(node, conditionsArray, callbackFunc));
                    }
                }
            }//endif
            return temp;
        }

        function clearSched() {
            let tablWrapper = document.querySelector('#schedulee')

            let blockss = schedulee.querySelectorAll('td')

            blockss.forEach(bl => {
                if (bl.id) {
                    bl.innerHTML = ''
                }
            });
        }


        function ShowElectiveCoursesByElCode(prms) {

            prms.object.innerHTML = '<img style="display:inline"  height="12" src="images/loading12.gif" />';
            periodNo = "";
            if (prms.period_no != "undefined") periodNo = prms.period_no;

            $.ajax({
                url: '/ShowElectiveCoursesByElCode',
                method: 'POST',
                data: {
                    sentFrom: prms.sentFrom,
                    dk: prms.dersKod,
                    muf_sq_id: prms.muf_sq_id,
                    period_no: periodNo,
                    group_title: prms.group_title,
                    pc: prms.progCode,
                    py: prms.progYear,
                    track: prms.progTrack,
                    ctp: prms.codeType,
                    lg: prms.lgCode,
                    type: prms.type
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    cb_ShowElectiveCoursesByElCode(response);
                },
                error: function (xhr, status, error) {
                    console.error('Ошибка при выполнении AJAX-запроса:', error);
                }
            });

        }

        function cb_ShowElectiveCoursesByElCode(res) {

            var cnt = document.getElementById("divCourseSearchType");
            cnt.style.display = 'block';

            if (res["CODE"] == 1) {
                if (showSems) {
                    swapShowProg();
                }
                showingMufSQ = true;
                cnt.innerHTML = res["DATA"];
                untoavaible()
            } else if (res["CODE"] < 0) {
                alert(res["DATA"]);
            }
        }


        function untoavaible() {
            // Найдем все элементы <tr> с классом "unavailable"
            document.querySelector('#tblSections')?.querySelectorAll('tr.unavailable').forEach(function (row) {
                // Получаем значение из атрибута "name", которое будет использоваться для selectSectionDiv
                let sectionId = row.getAttribute('name');

                // Меняем класс с "unavailable" на "selectable"
                row.classList.remove('unavailable');
                row.classList.add('selectable');

                // Добавляем атрибут onclick с нужным значением
                row.setAttribute('onclick', `selectSectionDiv('${sectionId}', 1)`);

                // Если нужно, можно добавить другие изменения
                // Например, изменить id, если это нужно
                row.setAttribute('id', `norm${sectionId}`);
                document.querySelectorAll('input[type="radio"][disabled]').forEach(function(radio) {
                    // Убираем атрибут disabled
                    radio.removeAttribute('disabled');
                });
            }); // Найдем все элементы <tr> с классом "unavailable"
            document.querySelector('#plDiv')?.querySelectorAll('tr.unavailable').forEach(function (row) {
                // Получаем значение из атрибута "name", которое будет использоваться для selectSectionDiv
                let sectionId = row.getAttribute('name');

                // Меняем класс с "unavailable" на "selectable"
                row.classList.remove('unavailable');
                row.classList.add('selectable');

                // Добавляем атрибут onclick с нужным значением
                row.setAttribute('onclick', `selectSectionDiv('${sectionId}')`);

                // Если нужно, можно добавить другие изменения
                // Например, изменить id, если это нужно
                row.setAttribute('id', `pl${sectionId}`);
                document.querySelectorAll('input[type="radio"][disabled]').forEach(function(radio) {
                    // Убираем атрибут disabled
                    radio.removeAttribute('disabled');
                });
            });
        }

    </script>
@endsection
