$(document).ready(function() {

  $("#advanceparameterslink").click(function(){
    $("#advanceparameterssection").toggle(1000);
  });

      $('#genesequence').trigger('autoresize');
      $('.modal').modal();
      $('select').material_select();
      var organisms = [];

      $.ajax({
        url: 'data/TaxData.json',
        async: false,
        dataType: 'json',
        success: function (response) {
	organisms["All()"] = "null";
          $.each(response, function(key, val) {
                  var options = "";
                  options = val.SpeciesName + '(' + val.NCBITaxID+')';
                  organisms[options] = "null";
               });
           }
         });
      console.log(organisms);
      $('input.autocomplete').autocomplete({
        data :organisms,
        limit : 10 // The max amount of results that can be shown at once. Default: Infinity.
      });

      var taxonomy = [];

      $( "#organismName" ).change(function() {
        $.ajax({
          url: 'data/TaxData.json',
          async: false,
          dataType: 'json',
          success: function (response) {
            var selectedOrganism = $('#organismName').val();
            var regularExpr = /\((.*)\)/;

            var selectedOrganismTaxID = selectedOrganism.match(regularExpr)[1];
            $.each(response, function(key, val) {
              var options = "";
              if(val.NCBITaxID == selectedOrganismTaxID){
                $('select').empty().html(' ');
                $.each(val.Taxonomy, function(key, val) {
                  var value = val.substr(9,val.length);
                  $('select').append($("<option></option>").attr("value",value).text(value));
                });
                // re-initialize (update)
                $('select').material_select();
              }
            });
          },
          error:function(error){
            alert(error);
          }
        });
      });

      $('#submit').click(function(){
        console.log('submit clicked');
        $('#modal1').modal('open');
      });

      $('#load-example-data').click(function(){
        console.log('#example link clicked');
        $('#genesequence').load('data/Ecoli_511145.fasta');
        $('#genesequence').addClass('active');
        $('#organismName').val('Escherichia coli str. K-12 substr. MG1655 (511145)');
        $.ajax({
          url: 'data/TaxData.json',
          async: false,
          dataType: 'json',
          success: function (response) {
            var selectedOrganism = $('#organismName').val();
            var regularExpr = /\((.*)\)/;

            var selectedOrganismTaxID = selectedOrganism.match(regularExpr)[1];
            $.each(response, function(key, val) {
              var options = "";
              if(val.NCBITaxID == selectedOrganismTaxID){
                $('select').empty().html(' ');
                $.each(val.Taxonomy, function(key, val) {
                  var value = val.substr(9,val.length);
                  $('select').append($("<option></option>").attr("value",value).text(value));
                });
                // re-initialize (update)
                $('select').material_select();
              }
              });
            },
               error:function(error){
               alert(error);
               }
            });
        $(function() {
          Materialize.updateTextFields();
          });
        return true;
          });

	  $("#fastafile").on('change', function() {
           
         //    $('#genesequence').trigger('autoresize');
         //     resizeTextArea($('#genesequence'));
         $('#genesequence').css('overflow-y', 'auto');
        });
    });

$('body').on('change focus', '#genesequence', function () {
         //console.log('r 2');
         $('#genesequence').css('overflow-y', 'auto');
         $('#genesequence').trigger('autoresize');
          //resizeTextArea($(this));
    });

//Optional but keep for future
function setFileContnet(val)
      {

    var file = document.getElementById("fastafile").files[0];
    var reader = new FileReader();
    reader.onload = function (e) {
    var textArea = document.getElementById("genesequence");
    textArea.value = e.target.result;
    };
        reader.readAsText(file);
       
    }

     function resizeTextArea($textarea){

            var hiddenDiv = $('.hiddendiv').first();
            if (!hiddenDiv.length) {
                hiddenDiv = $('<div class="hiddendiv common"></div>');
                $('body').append(hiddenDiv);
            }

            var fontFamily = $textarea.css('font-family');
            var fontSize = $textarea.css('font-size');

            if (fontSize) { hiddenDiv.css('font-size', fontSize); }
            if (fontFamily) { hiddenDiv.css('font-family', fontFamily); }

            if ($textarea.attr('wrap') === "off") {
                hiddenDiv.css('overflow-wrap', "normal")
                    .css('white-space', "pre");
            }

            hiddenDiv.text($textarea.val() + '\n');
            var content = hiddenDiv.html().replace(/\n/g, '<br>');
            hiddenDiv.html(content);
            console.log(  $textarea.val());

            // When textarea is hidden, width goes crazy.
            // Approximate with half of window size

            if ($textarea.is(':visible')) {
                hiddenDiv.css('width', $textarea.width());
            }
            else {
                hiddenDiv.css('width', $(window).width()/2);
            }

            $textarea.css('height', hiddenDiv.height());
            console.log(  hiddenDiv.height());
        }

