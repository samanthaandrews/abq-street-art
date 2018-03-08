import {Component, OnInit} from "@angular/core";
import {Art} from "../shared/classes/art";
import {ArtService} from "../shared/services/art.service";
import {Status} from "../shared/classes/status";

@Component({
    template: require("./art.component.html"),
})

export class ArtComponent implements OnInit {

    arts: Art[] = [];
    status: Status = null;

    constructor(
       private artService: ArtService,
    ) {}

    ngOnInit() : void {
        this.listArts();
    }

    listArts() : any {
        this.artService.artObserver()
           .subscribe(arts => this.arts = arts);
    }
}