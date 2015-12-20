<input type="submit" onclick="showRecords()" value="show" />

<!-- container div !--
<div id="container"></div>
<!-- main container temlate --
<div id="users" class="template">
    <div class="row"> <div class="col header">NAME</div>  <div class="col header">Nick Name</div> <div class="col header">Age </div> <div class="col header">Bitrh Day </div></div>
    __SLUG__ <!-- This slug will be replaced with parsed html of rows --
</div>
<!-- Template of a row must have Id followed by "Row"--
<div id="usersRow" class="template">
    <div class="row {grey}">
        <div class="col">{name}</div> <div class="col">{details.others.nickname}</div> <div class="col">{details.age}</div> <div class="col">{details.birthdate}</div>
    </div>
</div>
<script>
    // this is the function who parse the template
    //args
    // 1) tId : 'ID of the template div'
    // 2) row : json object containing values
    function teplateParser(tId,row)
    {
        var p = $('#'+tId).html();
        p = p.toString();
        var tempVars = getTemplateVars(p);
        for(a in tempVars)
        {
            var temp = tempVars[a];
            temp = temp.split('.');
            var val = row[temp[0]];
            if(val){
                for(k in temp)
                {
                    if(k==0)
                        continue;
                    if(val[temp[k]])
                        val = val[temp[k]];
                }
                p = p.replace('{'+tempVars[a]+'}',val);
            }
        }
        return p.toString();
    }
    // fetches the variable names from templates used in teplateParser
    function getTemplateVars(str) {
        var results = [], re = /{([^}]+)}/g, text;
        while(text = re.exec(str)) {
            results.push(text[1]);
        }
        return results;
    }

    // this function is perticularly made to display list of records. One can modify it according to needs
    function getParsedHtml(mainTId,data,replaceSlug)
    {
        var rows = '';
        var flipflop = true;
        for(a in data)
        {
            if(flipflop) data[a].grey = 'white'; else data[a].grey = 'grey'; // for alternate colors in rows
            rows+=teplateParser(mainTId+'Row',data[a]);
            flipflop = !flipflop;
        }
        var html = $('#'+mainTId).html();
        html = html.toString();
        html = html.replace(replaceSlug,rows);
        return html;
    }


    function showRecords()
    {
        // this variable may be loaded by ajax
        var Data = [ {name : 'Rupesh Patel' , details : { age : 25 , birthdate : '22 oct 1987' , others : {nickname : 'none'},}},
            {name : 'Rakesh Patel' , details : { age : 22 , birthdate : '14 Apr 1990',others : {nickname : 'rako'},},},
            {name : 'Nishith Patel' , details : { age : 23 , birthdate : '25 aug 1991',others : {nickname : 'nishu'},}},
            {name : 'Rajesh Patel' , details : { age : 30 , birthdate : '26 oct 1982',others : {nickname : 'raju'},}},
        ];
        $('#container').html(getParsedHtml('users',Data,'__SLUG__'));
    }

    (function(){
        showRecords();
    })();
</script>
</body>
</html>

<!--
</pre>
<script type="template" id="tweet-template">
    <div class="tweet">
        <a href="http://twitter.com/{{from_user}}" target="_blank">
            <img src="{{profile_image_url}}" class="tweet-image" />
        </a>
        {{text}}
    </div>
</script>
<div id="render-pihh">

</div>
<script>
    var data = [{
            "from_user":"JoeZimJS",
            "profile_image_url":"http://a0.twimg.com/profile_images/1508527935/logo_square_normal.png",
            "text":"\"Direct assault on Internet users.\" That's what ACLU calls a snooping bill moving thru House: http://t.co/0qu7S9DV via @demandprogress",
        },
        {
            "from_user":"JoeZimJS",
            "profile_image_url":"http://a0.twimg.com/profile_images/1508527935/logo_square_normal.png",
            "text":"@inserthtml Go right ahead. I'm sure others have thought of it as well.",
        }
    ];

    useTemplate = function(template, data) {
        var i = 0,
            len = data.length,
            html = '';

        // Replace the {{XXX}} with the corresponding property
        function replaceWithData(data_bit) {
            var html_snippet, prop, regex;

            for (prop in data_bit) {
                regex = new RegExp('{{' + prop + '}}', 'ig');
                html_snippet = (html_snippet || template).replace(regex, data_bit["from_user"]);
            }

            return html_snippet;
        }

        // Go through each element in the array and add the properties to the template
        for (; i < len; i++) {
            html += replaceWithData(data[i]);
        }

        // Give back the HTML to be added to the DOM
        console.log(html);
        //return html;
    };
    useTemplate(document.getElementById("tweet-template"),data);
</script>
-->