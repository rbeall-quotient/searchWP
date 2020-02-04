/**
 * Hide or reveal non-mini elements.
 * @param  {[string]}  classname class name for non-mini classes
 */
function toggle_non_minis(classname)
{
  //get expander element for object
  var expander = document.getElementById(classname + '-expander');
  //get object element
  var li = document.getElementById(classname + '-container');

  var doExpand = (expander.textContent == 'Expand');

  //toggle text for expander between 'Expand' and 'Collapse'
  if(doExpand)
  {
    expander.textContent = "Collapse";

    //highlight object if expanded
    li.classList.add('edan-search-object-highlight');
  }
  else
  {
    expander.textContent = 'Expand';

    //remove highlight if collapsed
    li.classList.remove('edan-search-object-highlight');
  }

  //get fields elements
  var elements = li.getElementsByClassName('edan-search-object-fields');

  //iterate through elements and display if hidden. Hide if currently displayed.
  for(var i = 0; i < elements.length; i++)
  {
    if (doExpand)
    {
      elements[i].style.display = "block";
    }
    else
    {
      elements[i].style.display = "none";
    }
  }
}

/**
 * Toggle all minimizable fields on clicking "Expand All" link
 */
function toggle_all()
{
  //get expandall link element
  var expandall  = document.getElementById('edan-search-expandall');
  //get all object containers
  var containers = document.getElementsByClassName('edan-search-object-container');
  //get all expander links
  var expanders   = document.getElementsByClassName('edan-search-expander');
  //get all minimizable fields
  var fields     = document.getElementsByClassName('edan-search-object-fields');

  //test whether or not to expand or collapse
  var doExpand = (expandall.textContent == 'Expand All');

  //if doExpand, change text to "Collapse All"
  if(doExpand)
  {
    expandall.textContent = 'Collapse All';
  }
  else
  {
    expandall.textContent = 'Expand All';
  }

  //highlight or remove highlighting from all containers
  for(var i = 0; i < containers.length; i++)
  {
    if(doExpand)
    {
      containers[i].classList.add('edan-search-object-highlight');
    }
    else
    {
      containers[i].classList.remove('edan-search-object-highlight');
    }
  }

  //set all expand links to collapse or vice-versa
  for(i = 0; i < expanders.length; i++)
  {
    if(doExpand)
    {
      expanders[i].textContent = 'Collapse';
    }
    else
    {
      expanders[i].textContent = 'Expand';
    }
  }

  //display or minimize all minimizable fields
  for(i = 0; i < fields.length; i++)
  {
    if(doExpand)
    {
      fields[i].style.display = "block";
    }
    else
    {
      fields[i].style.display = "none";
    }
  }

}
