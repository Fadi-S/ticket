
window.printTickets = element => {
    let printWindow = window.open('', '', 'height=400,width=800');
    printWindow.document.write('<html><body>');
    printWindow.document.write(element.innerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
    printWindow.close();
};