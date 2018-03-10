import {Component, OnInit} from "@angular/core";
import {ActivatedRoute, Params} from "@angular/router";
import {Status} from "../classes/status";
import {Observable} from "rxjs";
import "rxjs/add/observable/from";
import "rxjs/add/operator/switchMap";
import 'rxjs/add/observable/of';
import {ArtService} from "../services/art.service";
import {Art} from "../classes/art";
import {Point} from "../classes/point";


@Component({
    template: require("./map.component.html"),
    selector: "map"
})

export class MapComponent implements OnInit {

    // empty array of lat/long points

    art: Art = new Art(null, null, null, null, null, null, null, null, null, null);
    arts: Art[] = [];
    data: Observable<Array<Art[]>>;
    point: Point;

    constructor(
        protected artService : ArtService) {}

    ngOnInit() : void {
        // this.listArts();
    }

	listArts() : any {
		this.artService.artObserver
			.subscribe(arts => this.arts = arts);
	}

	extractPoints(art : Art) : any {
    	let point : Point = new Point(art.artLat, art.artLong);
      return(point);
   }

}