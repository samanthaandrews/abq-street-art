
import {Component, ViewChild, OnInit} from "@angular/core";
import {Router} from "@angular/router";
import {Observable} from "rxjs/Observable";
import {Status} from "../classes/status";
import {SignIn} from "../classes/sign.in";
import {CookieService} from "ngx-cookie-service";
import {setTimeout} from "timers";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {SignInService} from "../services/sign.in.service";

declare var $: any;


@Component({
    template: require("./sign-in-modal.component.html"),
    selector: "sign-in"
})

export class SignInModalComponent implements OnInit {

    signInForm: FormGroup;

    signin: SignIn = new SignIn(null, null);
    status: Status = null;


    constructor(private formBuilder: FormBuilder, private router: Router, private signInService: SignInService) {
    }

    ngOnInit(): void {
        this.signInForm = this.formBuilder.group({
                profileEmail: ["", [Validators.maxLength(128), Validators.required]],
                profilePassword: ["", [Validators.maxLength(48), Validators.required]],
            }
        );


    }

    signIn(): void {

        let signIn = new SignIn(this.signInForm.value.profileEmail, this.signInForm.value.profilePassword);

        this.signInService.postSignIn(signIn)
            .subscribe(status => {
                this.status = status;

                if (this.status.status === 200) {
                    this.router.navigate(["signed-in-homeview"]);
                }
            });
    }
}



