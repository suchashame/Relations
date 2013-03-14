%KEY = 123%
%CHATLANS<<<IGUST_2_0
"%NICK%",
IGUST_2_0%
%PAGE_1<<<IGUST_2_0
%MD5("result%KEY%")%
%PEOPLE('LIST', 0, -1, -1,     %CHATLANS%,     "%IF(%CFG('FormCheck')%, 'CHECKED', '')%|NO_LOCKED|NO_DELETED")%
IGUST_2_0%
%IF(%EQ(%GET("page")%, %MD5("chatlans%KEY%")%)%, %(%PAGE_1%)%,"")%








%KEY = 123%

<script
    src="http://igust4u.ru/service/igust-2.0/js/lib/jquery-1.7.1.min.js"></script>
<script
    src="http://igust4u.ru/service/igust-2.0/js/lib/JsHttpRequest.js"></script>
<script
    src="http://igust4u.ru/service/igust-2.0/js/lib/jquery-css-transform.js"></script>
<script src="http://igust4u.ru/service/igust-2.0/js/lib/rotate3Di.js"></script>
<script
    src="http://igust4u.ru/service/igust-2.0/js/chatlans.js.php?chat=%CHAT('CHAT')%&md5=%MD5("%CHAT('CHAT')%%KEY%")%"></script>
<
script
src = "http://igust4u.ru/service/igust-2.0/js/family.js" ></script>

<link rel="stylesheet" type="text/css"
      href="http://igust4u.ru/service/igust-2.0/css/igust-2.0.css"/>

%FAMILY<<<IGUST_2_0
<tr>
    <td colspan="2">
        <div class="inam_show capital">�����</div>
        <div id="family"><img class="loading_bar"
                              src="http://igust4u.ru/service/igust-2.0/img/loading-bar.gif"
                              alt=""/></div>
        <div id="family_debug"></div>
    </td>
</tr><script
    type="text/javascript">family['data'] = { "chat":"%CHAT('CHAT')%", "chat_id":"%CHAT('ID')%", "nick":"%NICK%", "nick_id": % PROFILE %, "user_id"
: %
USER('PROFILE') %, "sex"
:%
SEX %, "md5"
:
"%MD5(" % KEY % % CHAT('CHAT') % % CHAT('ID') % % NICK % % PROFILE % % USER('PROFILE') % % SEX % ")%"
}</script>
IGUST_2_0%






%IF(%USER('PROFILE')%,%(%FAMILY%)%,"")%