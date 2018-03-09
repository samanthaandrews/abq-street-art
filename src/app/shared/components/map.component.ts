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
    public positions: any = [];

    art: Art = new Art(null, null, null, null, null, null, null, null, null, null);
    arts: Art[] = [];
    data: Observable<Array<Art[]>>;
    artData: Point[] = [];


    constructor(
        protected artService : ArtService) {}

    ngOnInit() : void {

        // OnInit, make call to populate positions array thru the Observable
        this.getAllArtPoints();
    }

    getAllArtPoints(): void {
        this.artService.getArtPoints()
            .subscribe( artData => this.artData = artData);
    }




}