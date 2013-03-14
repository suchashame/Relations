var family = new Object();
var sb = 0; // sugbox exists test


family['torus'] = { "pair":"Муж | Жена", "parent":"Папа | Мама", "child":"Сын | Дочь", "grandparent":"Дедушка | Бабушка", "grandchild":"Внук | Внучка", "uncle":"Дядя | Тётя", "nephew":"Племянник | Племянница", "sibs":"Брат | Сестра", "friend":"Друг | Подруга", "lover":"Любовник | Любовница" };


family['proposal'] = "";
family['content'] = "";

family.main = function (action, info) {

    family['action'] = action;
    family['info'] = info;

    JsHttpRequest.query(
        'http://igust4u.ru/service/igust-2.0/family/' + family['action'],
        {
            'data':family['data'],
            //'gender':family.gender(),
            'info':family['info']
        },

        function (result, errors) {

            document.getElementById("family_debug").innerHTML = errors;

            if (result) {

                family['content'] = result["content"];
                family['proposal'] = result["proposal"];

                document.getElementById("family").innerHTML = family['content'];
            }
        },
        false
    );
};

var jq = jQuery.noConflict();

family.sel = function (thisis) {
    var torus = family['torus'];
    var newfill = "<div class='newfill'><label for='" + thisis + "'><div class='label'>" + torus[thisis] + ":&nbsp;</div><input onblur='hidesug();' onkeyup='checknick(event,this);' class='familynewfill' type='text' placeholder='Введите ник' id='" + thisis + "' /></label><img onclick='family.back(this," + thisis + ");' class='cancel' src='http://igust4u.ru/service/igust-2.0/img/cancel.png' alt='' /></div>";
    jq(newfill).insertAfter("#family_fill");
    var value = "[value='" + thisis + "']";
    jq(value).remove();
    jq(".familyadd").show();
    jq(".familytimeback").show();
};


family.back = function (vthis, vthisis) {
    jq(vthis).parent().remove();

    if (jq(".newfill").html() == null) {
        jq(".familyadd").hide();


    }

};

family.timeback = function () {
    family.main('', '')
    // TODO: Вместо машины времени сделать нормальное убирание и добавление полей
};


family.al = function () {
    var z;
    family['add'] = new Object();
    for (z in family['torus']) {
        if (jq("#" + z).val() != undefined) {
            family['add'][z] = jq("#" + z).val();
        }
    }
    family.main('add', family['add']);
};

family.proposals = function () {
    jq("#family").rotate3Di('flip',
        1000,
        {
            sideChange:showProposals,
            direction:'clockwise'
        }
    );
};

family.deproposals = function () {
    family.denot();
    jq("#family").rotate3Di('flip',
        1000,
        {
            sideChange:showDeProposals,
            direction:'anticlockwise'
            // TODO: Сделать чтобы картинка менялась после поворота плашки, а не до
        }
    );
};

family.denot = function () {

    family.main('denot', '');
};

family.chainunchain = function () {
    family["tochainunchain"] = [family["tochain"], family["tounchain"]];
    family.main('chainunchain', family["tochainunchain"]);
};


function showProposals() {
    jq("#family").html(family['proposal']);

}
function showDeProposals() {
    family["tochainunchain"] = [family["tochain"], family["tounchain"]];
    family.main('chainunchain', family["tochainunchain"]);
}


family["tochain"] = new Array();
family["tounchain"] = new Array();


function removeByValue(arr, val) {
    for (var i = 0; i < arr.length; i++) {
        if (arr[i] == val) {
            arr.splice(i, 1);
            break;
        }
    }
}


family.chain = function (id) {
    jq("#ch" + id).attr("src", "http://igust4u.ru/service/igust-2.0/img/chain.png");
    jq("#ch" + id).attr("onclick", "family.unchain(" + id + ");");
    family["tochain"].push(id);
    removeByValue(family["tounchain"], id);

};
family.unchain = function (id) {
    jq("#ch" + id).attr("src", "http://igust4u.ru/service/igust-2.0/img/unchain.png");
    jq("#ch" + id).attr("onclick", "family.chain(" + id + ");");
    family["tounchain"].push(id);
    removeByValue(family["tochain"], id);
};

family.toggle = function () {
    jq(".unchained").slideToggle();
};

family.fail = function (act, id) {
    switch (act) {
        case 2:

            family.main('fail', id);
            break;

        case 1:
            jq("#img" + id).show();
            break;
        case 0:
            jq("#img" + id).hide();
            break;

    }
};


// TODO: Сделать перемещение по коробочке стрелочками клавиатурки
function checknick(e,thisis) {
    var e = e || window.event;

    if (e.keyCode != 40 && e.keyCode != 38) {

    var letter = thisis.value;
    jq(".sugbox").remove();
    if (letter.length > 0) {

        var i = 0;
        var nicksug = new Array();
        var sugbox = "<div class='sugbox'>";
        for (var item in chl) {
            var nick = chl[item];

            if (nick.toLowerCase().indexOf(letter.toLowerCase()) == 0) {
                nicksug[i] = nick;

                sugbox += "<div class='pointer suggest' onmousedown='insert(this);'>" + nicksug[i] + "</div>";

                ++i;

            }


        }
        sugbox += "</div>";
        jq(sugbox).insertAfter(thisis);
        sb = 1;

    }
    jq(".suggest").hover(function () {
        jq(this).addClass("sugact");
    }, function () {
        jq(this).removeClass("sugact");
    });

    }
}


function hidesug() {

    jq(".sugbox").remove();
    sb = 0;
    i = 0;
}


function insert(thisis) {
    jq(thisis).parent().prev().val(thisis.innerHTML);
}


jq(document).ready(function () {

    jq("#family").keyup(function(e){
    var min = 0;
    var max = jq(".suggest").size();
        if(sb == 1) {
                switch (e.keyCode) {
                    case 40:
                        if (typeof i == 'undefined' || i >= jq(".suggest").size()) {
                            i = min;
                            var l =  jq(".suggest").size() - 1;
                            jq(".suggest:eq("+l+")").removeClass("sugact");
                        }
                        var l = i - 1;


                        jq(".suggest:eq("+i+")").addClass("sugact");
                        jq(".suggest:eq("+l+")").removeClass("sugact");
                        jq(".sugact").parent().prev().val("");
                        jq(".sugact").parent().prev().val(jq(".sugact").text());
                        i++;


                        break;
                    case 38:
                        if (typeof i == 'undefined' || i <= 1) {
                            i = max;
                            i++;
                            jq(".suggest:eq(0)").removeClass("sugact");
                            //i++;
                        }



                        i--;
                        i--;
                        var l = i + 1;
                        jq(".suggest:eq("+i+")").addClass("sugact");
                        jq(".suggest:eq("+l+")").removeClass("sugact");
                        jq(".sugact").parent().prev().val("");
                        jq(".sugact").parent().prev().val(jq(".sugact").text());
                        i++;


                        break;
                    case 39:
                        hidesug();
                        break;
                    default:
                        break;
                }
        }
    });

    family.timeback();


    jq(".capital").click(function () {
        jq(this).next().slideToggle();
        jq(this).toggleClass("inam_show");
        jq(this).toggleClass("inam_collapsed");

    });

});




