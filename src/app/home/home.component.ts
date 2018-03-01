import {Component, OnInit} from "@angular/core";
// import {User} from "../shared/classes/user";
// import {UserService} from "../shared/services/user.service";

@Component({
	template: require("./home.component.html")
})

// Once Angular is booted up, do the following:
export class HomeComponent {
	// State variable, an array of users, currently an empty placeholder.
	// users: User[] = [];

	constructor() {}

// Angular on init. What happens when the route loads, kind of Angular's version of "loaded"
// 	ngOnInit(): void {
// 		this.userService.getAllUsers()
//
// 		// Whatever we receive from the service, let's store it in this users array for later use in the DOM.
// 			.subscribe(users => this.users = users);
// 	}
}
