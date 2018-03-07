import {Component} from "@angular/core";
import {SignInService} from "../services/sign.in.service";

@Component({
    template: require("./navbar.component.html"),
    selector: "navbar"
})

export class NavbarComponent {

    constructor(private signInService: SignInService) {
    }

    logOut(): void {
        this.signInService.signOut();
    }
}