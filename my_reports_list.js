window.onload = function() {
  my_reports_list();
};

//αναλαμβάνει να εκτυπώσει τη λίστα με τις αναφορές
//-λειτουργεί σε συνδυασμό με το map_process.php αρχείο-

$(function() {
    my_reports_list();
});

function my_reports_list()
{	
	$.ajax({
		url: "map_process.php",
		data: "",
		success:function(data){	
			var htmlList = "<ul>";
			var count;
			var i=0;
			$(data).find("report").each(function () {
				count= $(this).attr('num_of_reports');	
				if (count > 0 ) {
					var category = $(this).attr('category');
					//η myclick καλεί τον μάρκερ που αντιστοιχεί
					//στη αναφορά από τη λίστα στην οποία κλικάραμε
					//η myclick ορίζεται στην new_report_map.js
					htmlList = htmlList.concat('<li><a href="javascript:myclick('+i+')"><p>Κατηγορία: '+category+'</p>');
					var description = '<p>'+ $(this).attr('description') +'</p>';
					htmlList = htmlList.concat('<p>Περιγραφή: '+description+'</p>');
					var status = $(this).attr('report_status');
					htmlList = htmlList.concat("<p>Κατάσταση: "+status+'</p>');
					var comment = $(this).attr('report_comment');
					if (comment != "" ) {
						htmlList = htmlList.concat("<p>Σχόλιο Διαχειριστή: <br>"+comment+'</p>');
					}
					var date = '<p>'+$(this).attr('datetime')+'</p>';
					htmlList = htmlList.concat("<p>Ημερομηνία Καταχώρησης: "+date+"</p></a>");
					htmlList = htmlList.concat("<p>__________________________</p><br></li>"); //fix it
					i++;
				}
				else count = 0;
			});
			htmlList = htmlList.concat("</ul>");
			var htmlReportsNum = "Οι συνολικές αναφορές σας στο σύστημα ειναι: "+count;
			$('#num_of_reports').html(htmlReportsNum);
			$('#my_reports_list').html(htmlList);
		}
	});
}
