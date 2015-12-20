<div id="container"></div>
<div id="users" class="template">
    <div class="row"> <div class="col header">NAME</div>  <div class="col header">Nick Name</div> <div class="col header">Age </div> <div class="col header">Bitrh Day </div></div>
    __SLUG__ <!-- This slug will be replaced with parsed html of rows -->
</div>
<div id="usersRow" class="template">
    <div class="row {grey}">
        <div class="col">{name}</div> <div class="col">{details.others.nickname}</div> <div class="col">{details.age}</div> <div class="col">{details.birthdate}</div>
    </div>
</div>