$(function () {    
    $('#CCC-tab').bind('click', function () {
        $('#all').html("");
        $.get("http://localhost/get/allQuestions").done(function (res) {
            question = res;
            for(let i = 0; i < question.length; i++)  {
                console.log(question[i]);
                $('#all').append('<div class="card card-task" style="height:150px"><div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div><div class="card-body"><div class="card-title"><a href="question/' + question[i].id + '"><h6 data-filter-by="text" style="font-size:20px">' + question[i].title + '</h6></a><p class="card-text"><small class="text-muted" style="font-size:12px">Last updated ' + question[i].creationDate + '</small></p><div class="card card-task" style="width:500px;padding:7px"><blockquote class="blockquote mb-0"><p class="card-text" style="font-size:17px">' + question[i].description + '</p></blockquote></div></div><div class="dropdown card-options"><div class="card-meta" style="margin-top:3px;margin-bottom:5px"><a href="/profile/visit/' + question[i].user.username + '" data-toggle="tooltip" title=""><img alt="Kenny Tran" class="avatar" src="uploads/picture/' + question[i].user.picture + '" /> <p>by ' + question[i].user.firstname + '</p> </a> </div><div class="card-meta" style="font-size:13px"><br /><p>' + question[i].targetDepartment.label + '</p></div></div></div></div >')
        ;} 
        });
    });

    $('#BBB-tab').bind('click', function() {
        $('#myQuestions').html("");
        $.get("http://localhost/get/myQuestions").done(function (res) {
            question = res;
            for(let i = 0; i < question.length; i++)  {
                console.log(question[i]);
                $('#myQuestions').append('<div class="card card-task" style="height:150px"><div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div><div class="card-body"><div class="card-title"><a href="question/' + question[i].id + '"><h6 data-filter-by="text" style="font-size:20px">' + question[i].title + '</h6></a><p class="card-text"><small class="text-muted" style="font-size:12px">Last updated ' + question[i].creationDate + '</small></p><div class="card card-task" style="width:500px;padding:7px"><blockquote class="blockquote mb-0"><p class="card-text" style="font-size:17px">' + question[i].description + '</p></blockquote></div></div><div class="dropdown card-options"><div class="card-meta" style="margin-top:3px;margin-bottom:5px"><a href="/profile/visit/' + question[i].user.username + '" data-toggle="tooltip" title=""><img alt="Kenny Tran" class="avatar" src="uploads/picture/' + question[i].user.picture + '" /> <p>by ' + question[i].user.firstname + '</p> </a> </div><div class="card-meta" style="font-size:13px"><br /><p>' + question[i].targetDepartment.label + '</p></div></div></div></div >')
        ;} 
        });
    });
    $('#AAA-tab').bind('click', function() {
        $.get("http://localhost/get/departmentQuestions").done(function (res) {
            question = res;
            $('#myDepartment').html("");
            for (let i = 0; i < question.length; i++) {
                console.log(question[i]);
                $('#myDepartment').append('<div class="card card-task" style="height:150px"><div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div><div class="card-body"><div class="card-title"><a href="question/' + question[i].id + '"><h6 data-filter-by="text" style="font-size:20px">' + question[i].title + '</h6></a><p class="card-text"><small class="text-muted" style="font-size:12px">Last updated ' + question[i].creationDate + '</small></p><div class="card card-task" style="width:500px;padding:7px"><blockquote class="blockquote mb-0"><p class="card-text" style="font-size:17px">' + question[i].description + '</p></blockquote></div></div><div class="dropdown card-options"><div class="card-meta" style="margin-top:3px;margin-bottom:5px"><a href="/profile/visit/' + question[i].user.username + '" data-toggle="tooltip" title=""><img alt="Kenny Tran" class="avatar" src="uploads/picture/' + question[i].user.picture + '" /> <p>by ' + question[i].user.firstname + '</p> </a> </div><div class="card-meta" style="font-size:13px"><br /><p>' + question[i].targetDepartment.label + '</p></div></div></div></div >')
                    ;
            }
        });
    });

   
    $.get("http://localhost/get/departmentQuestions").done(function (res) {
        question = res;
        $('#myDepartment').html("");
         for(let i = 0; i < question.length; i++)  {
            console.log(question[i]);
             $('#myDepartment').append('<div class="card card-task" style="height:150px"><div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div><div class="card-body"><div class="card-title"><a href="question/' + question[i].id + '"><h6 data-filter-by="text" style="font-size:20px">' + question[i].title + '</h6></a><p class="card-text"><small class="text-muted" style="font-size:12px">Last updated ' + question[i].creationDate + '</small></p><div class="card card-task" style="width:500px;padding:7px"><blockquote class="blockquote mb-0"><p class="card-text" style="font-size:17px">' + question[i].description + '</p></blockquote></div></div><div class="dropdown card-options"><div class="card-meta" style="margin-top:3px;margin-bottom:5px"><a href="/profile/visit/' + question[i].user.username + '" data-toggle="tooltip" title=""><img alt="Kenny Tran" class="avatar" src="uploads/picture/' + question[i].user.picture + '" /> <p>by ' + question[i].user.firstname + '</p> </a> </div><div class="card-meta" style="font-size:13px"><br /><p>' + question[i].targetDepartment.label + '</p></div></div></div></div >')
    ;} 
    });

    $("#goToTop").click(function () {
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return false;
    });

    $("#searchButton").click(function (e) {
        e.preventDefault();
        $.get("http://localhost/get/questions/" + $('#searchTerm').val() ).done(function (res) {
            question = res;
            $('#myDepartment').html("");
            $('#all').html("");
            $('#myQuestions').html("");
            for (let i = 0; i < question.length; i++) {
                console.log(question[i]);
                $('#myDepartment').append('<div class="card card-task" style="height:150px"><div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div><div class="card-body"><div class="card-title"><a href="question/' + question[i].id + '"><h6 data-filter-by="text" style="font-size:20px">' + question[i].title + '</h6></a><p class="card-text"><small class="text-muted" style="font-size:12px">Last updated ' + question[i].creationDate + '</small></p><div class="card card-task" style="width:500px;padding:7px"><blockquote class="blockquote mb-0"><p class="card-text" style="font-size:17px">' + question[i].description + '</p></blockquote></div></div><div class="dropdown card-options"><div class="card-meta" style="margin-top:3px;margin-bottom:5px"><a href="/profile/visit/' + question[i].user.username + '" data-toggle="tooltip" title=""><img alt="Kenny Tran" class="avatar" src="uploads/picture/' + question[i].user.picture + '" /> <p>by ' + question[i].user.firstname + '</p> </a> </div><div class="card-meta" style="font-size:13px"><br /><p>' + question[i].targetDepartment.label + '</p></div></div></div></div >');
                $('#all').append('<div class="card card-task" style="height:150px"><div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div><div class="card-body"><div class="card-title"><a href="question/' + question[i].id + '"><h6 data-filter-by="text" style="font-size:20px">' + question[i].title + '</h6></a><p class="card-text"><small class="text-muted" style="font-size:12px">Last updated ' + question[i].creationDate + '</small></p><div class="card card-task" style="width:500px;padding:7px"><blockquote class="blockquote mb-0"><p class="card-text" style="font-size:17px">' + question[i].description + '</p></blockquote></div></div><div class="dropdown card-options"><div class="card-meta" style="margin-top:3px;margin-bottom:5px"><a href="/profile/visit/' + question[i].user.username + '" data-toggle="tooltip" title=""><img alt="Kenny Tran" class="avatar" src="uploads/picture/' + question[i].user.picture + '" /> <p>by ' + question[i].user.firstname + '</p> </a> </div><div class="card-meta" style="font-size:13px"><br /><p>' + question[i].targetDepartment.label + '</p></div></div></div></div >');
                $('#myQuestions').append('<div class="card card-task" style="height:150px"><div class="progress"><div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div></div><div class="card-body"><div class="card-title"><a href="question/' + question[i].id + '"><h6 data-filter-by="text" style="font-size:20px">' + question[i].title + '</h6></a><p class="card-text"><small class="text-muted" style="font-size:12px">Last updated ' + question[i].creationDate + '</small></p><div class="card card-task" style="width:500px;padding:7px"><blockquote class="blockquote mb-0"><p class="card-text" style="font-size:17px">' + question[i].description + '</p></blockquote></div></div><div class="dropdown card-options"><div class="card-meta" style="margin-top:3px;margin-bottom:5px"><a href="/profile/visit/' + question[i].user.username + '" data-toggle="tooltip" title=""><img alt="Kenny Tran" class="avatar" src="uploads/picture/' + question[i].user.picture + '" /> <p>by ' + question[i].user.firstname + '</p> </a> </div><div class="card-meta" style="font-size:13px"><br /><p>' + question[i].targetDepartment.label + '</p></div></div></div></div >');
            }
        });
    });
});



