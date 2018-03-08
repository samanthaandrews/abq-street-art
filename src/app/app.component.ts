import {Component} from "@angular/core";
import {SessionService} from "./shared/services/session.service";

@Component({
    selector: "abq-street-art",
    template: require("./app.component.html")
})

export class AppComponent {
    constructor(
       protected sessionService: SessionService
    ){}
}