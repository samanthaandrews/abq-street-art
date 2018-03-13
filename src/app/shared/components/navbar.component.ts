import {Component} from "@angular/core";
import {SignInService} from "../services/sign.in.service";
import {CookieService} from "ngx-cookie-service";
import {Status} from "../classes/status";
import {Router} from "@angular/router";

@Component({
    template: require("./navbar.component.html"),
    selector: "navbar"
})

export class NavbarComponent {

    status: Status;

    constructor(
        private signInService: SignInService,
        private cookieService: CookieService,
        private router: Router
    ) {}

    //TODO change to logOut??
    signOut() : void {
        this.signInService.getSignOut()

            .subscribe(status => {
                this.status = status;
                if(status.status === 200) {

                    //delete cookies and jwt
                    this.cookieService.deleteAll();
                    localStorage.clear();

                    //send user back home, refresh page
                    this.router.navigate([""]);
                    location.reload();
                    console.log("goodbye");
                }
            });
    }
}