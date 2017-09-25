/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function getEmails () {
            var allVals = [];
            $('.senderEmail:checked').each(function () {
                debugger;
                allVals.push($(this).val());
            });
            $("#data").text(""+allVals);
        }
          
function selectText(element) {
            var doc = document;
            var text = doc.getElementById(element);    

         if (doc.body.createTextRange) { // ms
            var range = doc.body.createTextRange();
            range.moveToElementText(text);
            range.select();
         } else if (window.getSelection) { // moz, opera, webkit
            var selection = window.getSelection();            
            var range = doc.createRange();
            range.selectNodeContents(text);
            selection.removeAllRanges();
            selection.addRange(range);
        }   
        document.execCommand('copy');     
   }
   
    $(document).ready(function(){
    $('#check').click(function () {  
            $('input:checkbox').prop('checked', this.checked);    
        });
    });
 