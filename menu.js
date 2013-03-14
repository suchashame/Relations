function toggleLeftDiv(id) {

    var pos = id.lastIndexOf("/");
    id = id.substr(++pos);

    var left_cont = document.getElementById("left_cont");
    var id = document.getElementById(id);

    if (left_cont.style.display == "none") {
        id.style.display = "none";
        left_cont.style.display = "block";
    }
    else {
        id.style.display = "block";
        left_cont.style.display = "none";
    }

    return 0;

}