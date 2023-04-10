var app = app || angular.module('app', [], function($interpolateProvider){
	$interpolateProvider.startSymbol('<%');
	$interpolateProvider.endSymbol('%>');
});

app
.controller('userController',['$scope','$http',function($scope,$http){
	//$('#datatable-user').dataTable().api();
	$scope.deleteUser = function(id,THIS){
		if(window.confirm("Are you sure want to delete user ?")){
			$http
			.get("/user/delete/"+id)
			.then(
				function(response){
					if(response.data.status){
					
						alert("User deleted successfully !!");						 
						location.reload();
					}
					else{
						alert("Some error occcured !!");
						location.reload();
						 
					}
				},
				function(response){
					alert("Something went wrong !!");
					location.reload();
				}
			)
		}
	};
}])
.directive('dTableUser',['$compile',function($compile){
	return {
		restrict:'A',
		link:function(scope,elem,attrs){
			$(elem)
			.on('draw.dt',function(){
				$compile(elem.contents())(scope);
			})
			.dataTable({
				"fixedHeader":true,
				"processing":true,
				"serverSide":true,
				"paging":true,
				"ordering":false,
				"lengthMenu": [ 
					[10,25,50,100,250,500],
					['10','25','50','100','250','500']
				],
				"ajax":{
					"url":"/users/pagination",
					"data":function(d){						 
						d.page = (d.start/d.length)+1;
						d.columns = null;
						d.order = null;
					}
				}
			}).api();
		}
	};
}]);