//London Underground overtime tax estimator
function print(message) {
	document.write(message);
}

var salary = 0;
var salaryGrade = prompt("Enter your grade, CSS1, CSS2, CSA, CSA2 or your yearly salary");
if (salaryGrade.toUpperCase() === "CSS1") {
	salary = 45674;
	// print("Your grade " + salaryGrade.toUpperCase() + " = £" + salary);
} else if (salaryGrade.toUpperCase() === "SS2") {
	salary = 50321;
	//print("Your grade " + salaryGrade.toUpperCase() + " = £" + salary);
} else if (salaryGrade.toUpperCase() === "SS1") {
    salary = 55848;
	//print("Your grade " + salaryGrade.toUpperCase() + " = £" + salary);
} else if (salaryGrade.toUpperCase() === "CSS2") {
	salary = 37482;
	//print("Your grade " + salaryGrade.toUpperCase() + " = £" + salary);
} else if (salaryGrade.toUpperCase() === "CSA1") {
	salary = 31281;

} else if (salaryGrade.toUpperCase() === "CSA2") {
	salary = 23317;
	//print("Your grade " + salaryGrade.toUpperCase() + " = £" + salary);
} else {
	salary = salaryGrade;
	//alert("Please enter a valid grade");
}


var gradeSalary = document.getElementById('salary');
gradeSalary.textContent = '£ ' + salary;

var salary13 = salary / 13;

var salaryNoNI = salary13;

//National insurance contribution
function NI(salary13) {
    if (salary13 > 620 && salary13 <= 3304) {
	    var ni= (salary13 - 620) / 100 * 12;
	    return ni;

    } else if(salary13 > 3304.01){
	    var ni = (salary13 - 3304.01) / 100 * 2 + 322.08;
	    return ni;
	}
}
var ni = NI(salary13);
var natInsure = document.getElementById('ni');
natInsure.textContent = '£ ' + ni.toFixed(2);


var pension = prompt("Do you pay into the LUL pension?");


if (pension.toUpperCase() === "YES") {
	var pensionContribution = (salary13 - 444) / 100 * 5;//5772 is the lower earnings limit for pensions 2014/2015
	salary13 = salary13 - pensionContribution; //LUL pensions currently at 5% minus the lower earning limit
}

 //Tax
function taxCalc(salary13) {
    if (salary13 >= 846.15 && salary13 <= 3307.69) {
	    var tax = (salary13 - 846.15) / 100 * 20;
	    return tax;
    } else if (salary13 > 3307.70 && salary13 <= 11538) {
	    var tax = (salary13 - 3307.70) / 100 * 40 + 492.31;
	    return tax;
	}
}
var tax = taxCalc(salary13);
//var taxPay = document.getElementById('tax');
//taxPay.textContent = '£ ' + tax.toFixed(2);


var finalSalary = salary13  - tax - ni;

//var finalSalary1 = document.getElementById('finalsalary');
//finalSalary1.textContent = '£ ' + finalSalary.toFixed(2);

var monthly = finalSalary; //pay every 4 weeks so 13 times per year
var monthly1 = document.getElementById('monthly');
monthly1.textContent = '£ ' + monthly.toFixed(2);


//ot calculation
ot = parseFloat(prompt("How many hours overtime have you completed?"));
var ot1 = document.getElementById('overtime');
ot1.textContent = ot + ' hours';

var otCalculator = (salaryNoNI / 4 / 35) * (1.25 * ot);

var otEarn = document.getElementById('otearn');
otEarn.textContent = '£ ' + otCalculator.toFixed(2);

var salary1 = salary13 + otCalculator;
var salary2 = salaryNoNI + otCalculator;

var otNI = NI(salary2);//ot NI calc
var tax1 = taxCalc(salary1);//ot tax calc
////document.write('<p> OT NI = ' + otNI + '</p>');
//document.write('<p> OT TAX = ' + tax1 + '</p>');
//document.write('<p> TAX = ' + tax + '</p>');
//document.write('<p> NI = ' + ni + '</p>');
var otAfterTax = salary1 - tax1 - otNI - finalSalary;//this takes the difference between the overtime salary for the month minus the salary over 13
var total = monthly + otAfterTax;

var takehome = document.getElementById('takehome');
takehome.textContent = '£ ' + total.toFixed(2);

document.write("<h2>This is a rough estimate based on HMRC tax figures but your actual take home pay may differ due to changing tax codes, working tax credits and various other personal deductions.</h2>");
//the result of this was accurate within a £2 limit but does not take into account different tax codes, working tax credits etc and is therefore
//only a guide
