import {Component, OnInit} from "@angular/core";
// import {User} from "../shared/classes/user";
import {ArtService} from "../shared/services/art.service";
import {Art} from "../shared/classes/art";
import {AuthService} from "../shared/services/auth.service";

@Component({
    template: require("./signed-in-homeview.component.html")
})

// Once Angular is booted up, do the following:
export class SignedInHomeviewComponent implements OnInit{
    // State variable, an array of users, currently an empty placeholder.

    bookmarkedArt : Art[];

    constructor(
        protected artService : ArtService,
        private authService: AuthService
    ) {}

    getJwtProfileId() : any {
        if(this.authService.decodeJwt()) {
            return this.authService.decodeJwt().auth.profileId;
        } else {
            return false
        }
    }

// Angular on init. What happens when the route loads, kind of Angular's version of "loaded"
    ngOnInit(): void {
        this.getBookmarkedArt();
    }

    getBookmarkedArt() : void {
        let array : any[];

        this.artService.getAllArts().subscribe( arts=>{
            this.bookmarkedArt = arts;});

    }


}