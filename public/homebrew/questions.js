$(function () {    
    $('#CCC-tab').bind('click', function () {
        $('#all').html("");
        $.get("/get/allQuestions").done(function (res) {
            question = res;
            for(let i = 0; i < question.length; i++)  {
                let title = question[i].title;
                let newTitle = title.substring(0, 100);

                let currentDate = question[i].creationDate;
                let setDate = new Date(currentDate);
                let date = setDate.getFullYear() + "-" + setDate.getMonth() + "-" + setDate.getDay() + " at " + setDate.getHours() + ":" + setDate.getMinutes();
                $('#all').append('<div class="card card-task" style="height:10rem"><div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div><div class="card-body"><div class="card-title"><a href="question/' + question[i].id + '"><h6 data-filter-by="text" style="font-size:20px">' + newTitle + '</h6></a><p class="card-text"><small class="text-muted" style="font-size:12px">Last updated ' + date + '</small></p></div><div class="dropdown card-options"><div class="card-meta" style="margin-top:3px;margin-bottom:5px"><a href="/profile/visit/' + question[i].user.username + '" data-toggle="tooltip" title=""><img alt="Kenny Tran" class="avatar" style="" src="uploads/picture/' + question[i].user.picture + '" /> <p> ' + question[i].user.username + '</p> </a> </div><div class="card-meta" style="font-size:13px"><br /><p>' + question[i].targetDepartment.label + '</p></div></div></div></div >');
            } 
        });
    });

    $('#BBB-tab').bind('click', function() {
        $('#myQuestions').html("");
        $.get("/get/myQuestions").done(function (res) {
            question = res;
            for(let i = 0; i < question.length; i++)  {
                let title = question[i].title;
                let newTitle = title.substring(0, 100);
                let currentDate = question[i].creationDate;
                let setDate = new Date(currentDate);
                let date = setDate.getFullYear() + "-" + setDate.getMonth() + "-" + setDate.getDay() + " at " + setDate.getHours() + ":" + setDate.getMinutes();
                $('#myQuestions').append('<div class="card card-task" style="height:10rem"><div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div><div class="card-body"><div class="card-title"><a href="question/' + question[i].id + '"><h6 data-filter-by="text" style="font-size:20px">' + newTitle + '</h6></a><p class="card-text"><small class="text-muted" style="font-size:12px">Last updated ' + date + '</small></p></div><div class="dropdown card-options"><div class="card-meta" style="margin-top:3px;margin-bottom:5px"><a href="/profile/visit/' + question[i].user.username + '" data-toggle="tooltip" title=""><img alt="Kenny Tran" class="avatar" style="" src="uploads/picture/' + question[i].user.picture + '" /> <p> ' + question[i].user.username + '</p> </a> </div><div class="card-meta" style="font-size:13px"><br /><p>' + question[i].targetDepartment.label + '</p></div></div></div></div >');
            } 
        });
    });
    $('#AAA-tab').bind('click', function() {
        $.get("/get/departmentQuestions").done(function (res) {
            question = res;
            $('#myDepartment').html("");
            for (let i = 0; i < question.length; i++) {
                let title = question[i].title;
                let newTitle = title.substring(0, 100);
                let currentDate = question[i].creationDate;
                let setDate = new Date(currentDate);
                let date = setDate.getFullYear() + "-" + setDate.getMonth() + "-" + setDate.getDay() + " at " + setDate.getHours() + ":" + setDate.getMinutes();
                $('#myDepartment').append('<div class="card card-task" style="height:10rem"><div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div><div class="card-body"><div class="card-title"><a href="question/' + question[i].id + '"><h6 data-filter-by="text" style="font-size:20px">' + newTitle + '</h6></a><p class="card-text"><small class="text-muted" style="font-size:12px">Last updated ' + date + '</small></p></div><div class="dropdown card-options"><div class="card-meta" style="margin-top:3px;margin-bottom:5px"><a href="/profile/visit/' + question[i].user.username + '" data-toggle="tooltip" title=""><img alt="Kenny Tran" class="avatar" style="" src="uploads/picture/' + question[i].user.picture + '" /> <p> ' + question[i].user.username + '</p> </a> </div><div class="card-meta" style="font-size:13px"><br /><p>' + question[i].targetDepartment.label + '</p></div></div></div></div >');
            }
        });
    });

   
    $.get("/get/departmentQuestions").done(function (res) {
        question = res;
        $('#myDepartment').html("");
         for(let i = 0; i < question.length; i++)  {
             let title = question[i].title;
             let newTitle = title.substring(0, 100);
             let currentDate = question[i].creationDate;
             let setDate = new Date(currentDate);
             let date = setDate.getFullYear() + "-" + setDate.getMonth() + "-" + setDate.getDay() + " at " + setDate.getHours() + ":" + setDate.getMinutes();
             $('#myDepartment').append('<div class="card card-task" style="height:10rem"><div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div><div class="card-body"><div class="card-title"><a href="question/' + question[i].id + '"><h6 data-filter-by="text" style="font-size:20px">' + newTitle + '</h6></a><p class="card-text"><small class="text-muted" style="font-size:12px">Last updated ' + date + '</small></p></div><div class="dropdown card-options"><div class="card-meta" style="margin-top:3px;margin-bottom:5px"><a href="/profile/visit/' + question[i].user.username + '" data-toggle="tooltip" title=""><img alt="Kenny Tran" class="avatar" style="" src="uploads/picture/' + question[i].user.picture + '" /> <p> ' + question[i].user.username + '</p> </a> </div><div class="card-meta" style="font-size:13px"><br /><p>' + question[i].targetDepartment.label + '</p></div></div></div></div >');
         }
    });

    $("#goToTop").click(function () {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return false;
    });

    $("#searchButton").click(function (e) {
        e.preventDefault();
        $.get("/get/questions/" + $('#searchTerm').val() ).done(function (res) {
            question = res;
            $('#myDepartment').html("");
            $('#all').html("");
            $('#myQuestions').html("");
            for (let i = 0; i < question.length; i++) {
                let title = question[i].title;
                let newTitle = title.substring(0, 100);
                let currentDate = question[i].creationDate;
                let setDate = new Date(currentDate);
                let date = setDate.getFullYear() + "-" + setDate.getMonth() + "-" + setDate.getDay() + " at " + setDate.getHours() + ":" + setDate.getMinutes();
                $('#all').append('<div class="card card-task" style="height:10rem"><div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div><div class="card-body"><div class="card-title"><a href="question/' + question[i].id + '"><h6 data-filter-by="text" style="font-size:20px">' + newTitle + '</h6></a><p class="card-text"><small class="text-muted" style="font-size:12px">Last updated ' + date + '</small></p></div><div class="dropdown card-options"><div class="card-meta" style="margin-top:3px;margin-bottom:5px"><a href="/profile/visit/' + question[i].user.username + '" data-toggle="tooltip" title=""><img alt="Kenny Tran" class="avatar" style="" src="uploads/picture/' + question[i].user.picture + '" /> <p> ' + question[i].user.username + '</p> </a> </div><div class="card-meta" style="font-size:13px"><br /><p>' + question[i].targetDepartment.label + '</p></div></div></div></div >');
                $('#myQuestions').append('<div class="card card-task" style="height:10rem"><div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div><div class="card-body"><div class="card-title"><a href="question/' + question[i].id + '"><h6 data-filter-by="text" style="font-size:20px">' + newTitle + '</h6></a><p class="card-text"><small class="text-muted" style="font-size:12px">Last updated ' + date + '</small></p></div><div class="dropdown card-options"><div class="card-meta" style="margin-top:3px;margin-bottom:5px"><a href="/profile/visit/' + question[i].user.username + '" data-toggle="tooltip" title=""><img alt="Kenny Tran" class="avatar" style="" src="uploads/picture/' + question[i].user.picture + '" /> <p> ' + question[i].user.username + '</p> </a> </div><div class="card-meta" style="font-size:13px"><br /><p>' + question[i].targetDepartment.label + '</p></div></div></div></div >');$('#myDepartment').append('<div class="card card-task" style="height:10rem"><div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div><div class="card-body"><div class="card-title"><a href="question/' + question[i].id + '"><h6 data-filter-by="text" style="font-size:20px">' + newTitle + '</h6></a><p class="card-text"><small class="text-muted" style="font-size:12px">Last updated ' + date + '</small></p></div><div class="dropdown card-options"><div class="card-meta" style="margin-top:3px;margin-bottom:5px"><a href="/profile/visit/' + question[i].user.username + '" data-toggle="tooltip" title=""><img alt="Kenny Tran" class="avatar" style="" src="uploads/picture/' + question[i].user.picture + '" /> <p> ' + question[i].user.username + '</p> </a> </div><div class="card-meta" style="font-size:13px"><br /><p>' + question[i].targetDepartment.label + '</p></div></div></div></div >');
                $('#myDepartment').append('<div class="card card-task" style="height:10rem"><div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div><div class="card-body"><div class="card-title"><a href="question/' + question[i].id + '"><h6 data-filter-by="text" style="font-size:20px">' + newTitle + '</h6></a><p class="card-text"><small class="text-muted" style="font-size:12px">Last updated ' + date + '</small></p></div><div class="dropdown card-options"><div class="card-meta" style="margin-top:3px;margin-bottom:5px"><a href="/profile/visit/' + question[i].user.username + '" data-toggle="tooltip" title=""><img alt="Kenny Tran" class="avatar" style="" src="uploads/picture/' + question[i].user.picture + '" /> <p> ' + question[i].user.username + '</p> </a> </div><div class="card-meta" style="font-size:13px"><br /><p>' + question[i].targetDepartment.label + '</p></div></div></div></div >');
            }
        });
    });
});