/*
 this component is for signing up to use the site.
 */

//import needed modules for the sign-up component
import {Component, OnInit, ViewChild,} from "@angular/core";
import {Observable} from "rxjs/Observable"
import {Router} from "@angular/router";
import {Status} from "../shared/classes/status";
import {SignUp} from "../shared/classes/sign.up";
import {setTimeout} from "timers";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {SignUpService} from "../shared/services/sign.up.service";

//declare $ for good old jquery
declare let $: any;

// set the template url and the selector for the ng powered html tag
@Component({
    template: require("./sign-up.component.html")
})

export class SignUpComponent implements OnInit{

    signUpForm : FormGroup;

    signUp: SignUp = new SignUp(null, null, null, null);
    status: Status = null;


    constructor(private formBuilder : FormBuilder, private router: Router, private signUpService: SignUpService) {}

    ngOnInit()  : void {
        this.signUpForm = this.formBuilder.group({
            profileUserName: ["", [Validators.maxLength(32), Validators.required]],
            profileEmail: ["", [Validators.maxLength(128), Validators.required]],
            profilePassword:["", [Validators.maxLength(48), Validators.required]],
            profilePasswordConfirm:["", [Validators.maxLength(48), Validators.required]]

        });

    }

    createSignUp(): void {

        let signUp =  new SignUp(this.signUpForm.value.profileEmail, this.signUpForm.value.profileUserName, this.signUpForm.value.profilePassword, this.signUpForm.value.profilePasswordConfirm);

        this.signUpService.createProfile(signUp)
            .subscribe(status => {
                this.status = status;

                if(this.status.status === 200) {
                    alert(status.message);
                    setTimeout(function() {
                        $("#signUpForm").modal('hide');
                    }, 500);
                    this.router.navigate([""]);
                }
            });
    }
}