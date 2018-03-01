// Custom routes and fake URLs - this is what makes Angular cool
import {RouterModule, Routes} from "@angular/router";
import {HomeComponent} from "./home/home.component";
import {APP_BASE_HREF} from "@angular/common";
// import {Shared} from "./shared/services/";


// Every route you wish to express is a component
export const allAppComponents = [HomeComponent];

export const routes: Routes = [
	// Use our fake URLs - the browser will automatically swap in data.
	// The following is the default path - needs to come last in your array.
	// Sort your routes by most specific to least specific. Empty string "" matches everything.
    {path: "", component: HomeComponent}
];

export const appRoutingProviders: any[] = [
   {provide:APP_BASE_HREF, useValue: window["_base_href"]}
	// Services is a way to connect to data: your own or external service (others' data). We only have one for this project. Typically one service per API. AJAX services.
   // UserService
];

export const routing = RouterModule.forRoot(routes);

