  /**
   * register callback functions
   */
  var testCallback = {
    simpleText: function(result) 
    {
      document.getElementById('ajaxSimpleText').innerHTML += '<p>'+result+'</p>';
    },
    showAlertBox: function() 
    {
    },
    calculate: function(result) 
    {
      document.getElementById('ajaxResult').innerHTML = result;
    },  
    search: function(result) 
    {
        document.getElementById('ajaxSearch').innerHTML = '<dl>';

	if(result.length == 0)
	{
	    document.getElementById('ajaxSearch').innerHTML += '<dd class="articlenodebranch">no result</dd>';
	}
	else
	{
	    for (var i = 0; i < result.length; ++i)
	    {
	        document.getElementById('ajaxSearch').innerHTML += '<dd class="articlenodebranch">';	    
	    	
	    	// build the node branch of an article
	    	for (var b = 0; b < result[i]['nodeBranch'].length; ++b)
	    	{
      	            document.getElementById('ajaxSearch').innerHTML += '<a href="'+base+'/id_node/'+result[i]['nodeBranch'][b]['id_node']+'">'+result[i]['nodeBranch'][b]['title']+'</a>/';	    	
	    	}
      	        
      	        // print the node of an article
      	        document.getElementById('ajaxSearch').innerHTML += '<a href="'+base+'/id_node/'+result[i]['id_node']+'">'+result[i]['node']['title']+'</a>/</dd>';	    	    	
      	        
      	        // print article link title
      	        document.getElementById('ajaxSearch').innerHTML += '<dd class="articletitle"> - <a href="'+result[i]['id_article']+'">'+result[i]['title']+'</a></dd>';
      	    }
      	}
      	document.getElementById('ajaxSearch').innerHTML += '</dl>';
      	
      	// reset search button text
      	document.getElementById('dosearch').value = 'search';
    }   
  }

/**
* Calculate function
*/
function doCalculation() 
{
    // Create object with values of the form
    var objTemp = new Object();
    objTemp['number1'] = document.getElementById('number1').value;
    objTemp['number2'] = document.getElementById('number2').value;

    remoteTest.calculate(objTemp);
}

/**
* Search function
*/
function doSearch( s ) 
{
    // change search button text
    document.getElementById('dosearch').value = '... please wait';
    
    // Create object with values of the form field
    var objTemp = new Object();
    objTemp['search'] = document.getElementById('articlesearch').value;

    // launch remote search
    remoteTest.search(objTemp);
}

// create our remote object
var remoteTest = new ControllerArticleAjax(testCallback);